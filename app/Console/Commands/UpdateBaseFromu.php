<?php

namespace App\Console\Commands;

use App\Http\Services\Checker\Fromu\Fromu;
use Illuminate\Console\Command;

class UpdateBaseFromu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:base-fromu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списков ФРОМУ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Fromu::updateData();
    }
}
