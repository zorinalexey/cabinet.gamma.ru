<?php

namespace App\Http\Services\Checker\Passport;

use App\Models\NotValidPassport;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class Passport
{
    private static string $url = '';

    private ?string $fileBz2 = null;

    private ?string $dir = null;

    private ?string $fileCsv = null;

    public function __construct()
    {
        $config = config('company_details');
        $dir = $config['root_catalog'].DIRECTORY_SEPARATOR.'private'.
            DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'list'.DIRECTORY_SEPARATOR;
        $this->fileBz2 = $dir.'passports.csv.bz2';
        $this->fileCsv = $dir.'list_of_expired_passports.csv';
        $this->dir = dirname($this->fileBz2);
    }

    public function uploadPassportData(): void
    {
        if (! is_dir($this->dir) && ! mkdir($this->dir, 0777, true) && ! is_dir($this->dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->dir));
        }

        $command = "
            cd {$this->dir}
            wget -c -O {$this->fileBz2} https://xn--b1agjhrfhd.xn--b1ab2a0a.xn--b1aew.xn--p1ai/upload/expired-passports/list_of_expired_passports.csv.bz2
        ";
        system($command);

        if (! file_exists($this->fileCsv) && file_exists($this->fileBz2)) {
            $command = "
                cd {$this->dir}
                echo 'Распаковка архива запущена'
                unzip -x {$this->fileBz2}
                echo 'Распаковка архива завершена'
                rm {$this->fileBz2}
            ";
            system($command);
        } else {
            system("echo 'Имеется не обработанный файл базы'");
        }
    }

    public function updateBase(): void
    {
        if (is_file($this->fileCsv)) {
            $this->updateData($this->fileCsv);
        }
    }

    private function updateData(string $file): void
    {
        $handle = fopen($file, 'ab+');
        $query = NotValidPassport::query();
        $i = 0;
        $add = 0;

        while (($data = fgetcsv($handle, 1000)) !== false) {
            set_time_limit(600);

            if (! isset($data[0])) {
                $data[0] = null;
            }

            if (! isset($data[1])) {
                $data[1] = null;
            }

            if ($data[0] !== 'PASSP_SERIES' && $data[1] !== 'PASSP_NUMBER') {
                try {
                    DB::transaction(static function () use (&$query, $data, &$add) {
                        $passport = $query->create(
                            [
                                'series' => $data[0],
                                'number' => $data[1],
                            ]
                        );

                        dump([$passport->id, $data]);
                        $add++;
                        unset($passport);
                    });
                } catch (\Exception $e) {
                    dump($i);
                }

                $i++;
            }

            unset($data);

            if ($add >= 2000000) {
                break;
            }
        }

        unlink($file);
    }

    public function __destruct()
    {
        system('rm -r '.$this->dir);
    }
}
