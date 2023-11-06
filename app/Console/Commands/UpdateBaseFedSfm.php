<?php

namespace App\Console\Commands;

use App\Http\Services\Checker\FedSfm\FedSfm;
use Illuminate\Console\Command;

class UpdateBaseFedSfm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:base-rfm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление базы записей РосФинМониторинг';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FedSfm::updateData();
    }
}
