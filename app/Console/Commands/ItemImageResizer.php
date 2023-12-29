<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ImageResizeController;

class ItemImageResizer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itemImage:resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ресайз изображений товаров для каталожной выдачи';

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
        ImageResizeController::index();
    }
}
