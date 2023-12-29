<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GoogleSheetController;


class GoodleSheetsDealer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:dealer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск формирования Гугл таблиц для ДИЛЕРОВ';

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
        GoogleSheetController::shops();
    }
}
