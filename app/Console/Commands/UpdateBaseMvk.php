<?php

namespace App\Console\Commands;

use App\Http\Services\Checker\Mvk\Mvk;
use Illuminate\Console\Command;

class UpdateBaseMvk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:base-mvk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списков МВК';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mvk::updateData();
    }
}
