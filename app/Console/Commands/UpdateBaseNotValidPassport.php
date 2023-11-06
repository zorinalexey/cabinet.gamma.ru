<?php

namespace App\Console\Commands;

use App\Http\Services\Checker\Passport\Passport;
use Illuminate\Console\Command;

class UpdateBaseNotValidPassport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:base-passports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление базы не действительных паспортов';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 6000);
        $passportBase = new Passport();
        $passportBase->uploadPassportData();
        $passportBase->updateBase();
    }
}
