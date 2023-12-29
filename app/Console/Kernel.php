<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\GoodleSheets',
        'App\Console\Commands\GoodleSheetsDealer',
        'App\Console\Commands\ItemImageResizer',
        'App\Console\Commands\AnalogListMaker',
        'App\Console\Commands\DiscountRetype',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('google:sheets')
                 ->everyTenMinutes(); // для 21 век
        $schedule->command('google:dealer')
                 ->everyTenMinutes(); // для дилеров
        $schedule->command('itemImage:resize')
                 ->everyTenMinutes(); // ресайз изображений каждые 10 мин
        $schedule->command('analogList:maker')
                 ->hourlyAt(25); // прописываем аналоги для запчастей в БД
        $schedule->command('discount:retype')
                 ->dailyAt('01:00'); // обновляем дисконты каждую ночь в 1 час
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
