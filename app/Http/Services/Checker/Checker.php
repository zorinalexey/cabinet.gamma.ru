<?php

namespace App\Http\Services\Checker;

use Illuminate\Support\Facades\DB;
use JsonException;
use RuntimeException;
use SimpleXMLElement;

abstract class Checker
{
    /**
     * @throws JsonException
     */
    protected static function start(string $className): void
    {
        set_time_limit(60 * 60 * 24 * 5);
        ini_set('memory_limit', '1G');
        $data = false;
        $table = (new $className())->getTable();
        $xmlFile = self::getFilePuth();
        if (is_file($xmlFile)) {
            $data = simplexml_load_string(file_get_contents($xmlFile));
        }
        if ($data) {
            DB::table($table)->truncate();
            DB::beginTransaction();
            static::saveData($data);
            DB::commit();
        }
    }

    protected static function getFilePuth(): string
    {
        $config = config('company_details');
        $file = $config['root_catalog'].DIRECTORY_SEPARATOR.'private'.DIRECTORY_SEPARATOR.
            'files'.DIRECTORY_SEPARATOR.static::$fileName;
        $dir = dirname($file);
        if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        return $file;
    }

    abstract protected static function saveData(SimpleXMLElement $data): void;

    protected static function clear(string $str): string
    {
        return trim(preg_replace('~(\s{2,})~u', ' ', str_replace(["\t", "\n"], '', $str)));
    }
}
