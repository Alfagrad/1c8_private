<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GoogleSheetController;


class GoodleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск формирования Гугл таблиц каждые полчаса';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        GoogleSheetController::index();
        // GoogleSheetController::shops();
    }
}
