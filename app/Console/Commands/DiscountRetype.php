<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api_1c8\DiscountController;


class DiscountRetype extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discount:retype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'удаляем просроченые дисконты, переписываем строки для общих';

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
        DiscountController::discountRetype();
    }
}
