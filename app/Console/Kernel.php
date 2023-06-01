<?php

namespace App\Console;

use App\Jobs\ImportAset;
use App\Jobs\ImportCOA;
use App\Jobs\ImportDataAng;
use App\Jobs\ImportRefStatus;
use App\Jobs\RekapAnggaran;
use App\Jobs\RekapAnggaranMingguan;
use App\Jobs\RekapCashPlanTriwulan;
use App\Jobs\RekapDataAnggaran;
use App\Jobs\RekapRealisasiHarian;
use App\Jobs\UpdateStatusAktifAnggaran;
use App\Jobs\UpdateStatusImportRefStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ImportSppHeader;
use App\Jobs\UpdateUnitId;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $TA = date('Y');

        $schedule->job(new RekapCashPlanTriwulan($TA))->monthlyOn(1,'00:01');

        $schedule->job(new RekapAnggaranMingguan($TA))->weeklyOn(1,'05:00');

        $schedule->call(function () use ($TA){
           ImportSppHeader::withChain([
               new ImportCOA($TA),
               new RekapRealisasiHarian($TA)
           ])->dispatch($TA);
        })->twiceDailyAt(7,15);

        $schedule->call(function () use ($TA){
            ImportRefStatus::withChain([
                new UpdateStatusImportRefStatus($TA),
                new ImportDataAng($TA),
                new RekapAnggaran($TA),
                new UpdateStatusAktifAnggaran($TA),
                new UpdateStatusImportRefStatus($TA)
            ])->dispatch($TA);
        })->weeklyOn(1,'01:00');


        //$schedule->job(new ImportAset($TA))->dailyAt('19:13');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
