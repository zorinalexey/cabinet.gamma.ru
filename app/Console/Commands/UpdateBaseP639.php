<?php

namespace App\Console\Commands;

use App\Http\Services\Checker\P639\P639;
use Illuminate\Console\Command;

class UpdateBaseP639 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:base-p639';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списков П-639';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        P639::updateData();
    }
}
