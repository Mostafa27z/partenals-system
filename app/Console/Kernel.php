<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * تعريف الأوامر الخاصة بالتطبيق.
     */
    protected $commands = [
        // مثال: \App\Console\Commands\CheckLinePayments::class,
    ];

    /**
     * تعريف الجدولة الزمنية للأوامر.
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->command('lines:check-payments')->dailyAt('00:00');
}


    /**
     * تسجيل الأوامر من المجلد.
     */
    protected function commands()
    {
        $this->load(app_path('Console/Commands'));

        require base_path('routes/console.php');
    }
    
}
