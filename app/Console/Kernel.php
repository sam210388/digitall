<?php

namespace App\Console;

use App\Jobs\CetakDBRFinal;
use App\Jobs\DeleteDataAset;
use App\Jobs\HapusAnggaranInaktif;
use App\Jobs\HitungIkpaDeviasiBagian;
use App\Jobs\HitungIkpaKontraktualBagian;
use App\Jobs\HitungIkpaPenyelesaianBagian;
use App\Jobs\HitungIkpaPenyerapanBagian;
use App\Jobs\ImportAset;
use App\Jobs\ImportCOA;
use App\Jobs\ImportDataAng;
use App\Jobs\ImportDataAngSeluruh;
use App\Jobs\ImportKontrakCOA;
use App\Jobs\ImportKontrakHeader;
use App\Jobs\ImportRealisasiSakti;
use App\Jobs\ImportRealisasiSemar;
use App\Jobs\ImportRefStatus;
use App\Jobs\RekapAnggaran;
use App\Jobs\RekapAnggaranMingguan;
use App\Jobs\RekapCashPlanIKPABulanan;
use App\Jobs\RekapCashPlanTriwulan;
use App\Jobs\RekapDataAset;
use App\Jobs\RekapDataBarangDBR;
use App\Jobs\RekapKegiatanMingguan;
use App\Jobs\RekapRealisasiHarian;
use App\Jobs\RekonDataAngSeluruh;
use App\Jobs\UpdateIndetitasKinerja;
use App\Jobs\UpdateRegisterAset;
use App\Jobs\UpdateStatusAktifAnggaran;
use App\Jobs\UpdateStatusHapus;
use App\Jobs\UpdateStatusHenti;
use App\Jobs\UpdateStatusImportRefStatus;
use App\Jobs\UpdateStatusPengeluaran;
use App\Jobs\UpdateStatusUsul;
use App\Jobs\UpdateUnitId;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ImportSppHeader;


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
        //$TA = date('Y');
        $TA='2024';
        //rekap anggaran
        //$schedule->job(new RekapAnggaranMingguan($TA))->weeklyOn(1,'05:00');
        $schedule->job(new RekapAnggaranMingguan($TA))->dailyAt('02:25');

        //$schedule->job(new RekapKegiatanMingguan($TA))->dailyAt('10:55');

        $schedule->job(new UpdateUnitId($TA))->dailyAt('18:06');

        $schedule->job(new UpdateIndetitasKinerja($TA))->dailyAt('02:07');

        $schedule->job(new HitungIkpaPenyerapanBagian($TA))->dailyAt('13:23');

        $schedule->job(new HitungIkpaKontraktualBagian($TA))->dailyAt('15:10');

        $schedule->job(new ImportKontrakHeader($TA))->dailyAt('18:08');

        $schedule->job(new ImportKontrakCOA($TA))->dailyAt('23:08');

        $schedule->job(new HitungIkpaPenyelesaianBagian($TA))->dailyAt('19:09');

        $schedule->job(new HitungIkpaDeviasiBagian($TA))->dailyAt('08:04');

        //$schedule->job(new CetakDBRFinal())->dailyAt('16:01');

        $schedule->job(new ImportRealisasiSemar($TA))->everyFourHours();



        //$schedule->job(new RekapCashPlanTriwulan($TA))->monthlyOn(1,'00:01');
        $schedule->job(new RekapCashPlanTriwulan($TA))->dailyAt('04:40');

        $schedule->job(new RekapCashPlanIKPABulanan($TA))->dailyAt('15:16');

        //$schedule->job(new RekonDataAngSeluruh($TA))->dailyAt('23:46');
        //$schedule->job(new RekapRealisasiHarian($TA))->dailyAt('04:45');

        //$schedule->job(new ImportCOA($TA))->dailyAt('05:00');

        $schedule->call(function () use ($TA){
           ImportSppHeader::withChain([
               new UpdateStatusPengeluaran($TA),
               new ImportCOA($TA),
               new ImportRealisasiSakti($TA),
               new RekapRealisasiHarian($TA)
           ])->dispatch($TA);
        })->dailyAt('03:44');


        $schedule->call(function () use ($TA){
            ImportRefStatus::withChain([
                new UpdateStatusImportRefStatus($TA),
                new ImportDataAngSeluruh($TA),
                new UpdateStatusAktifAnggaran($TA),
                new RekapAnggaran($TA),
                new HapusAnggaranInaktif($TA),
                new RekonDataAngSeluruh($TA)
            ])->dispatch($TA);
        })->dailyAt('09:00');


        $schedule->call(function () use ($TA){
            DeleteDataAset::withChain([
                new ImportAset($TA),
                new RekapDataAset(),
                new RekapDataBarangDBR()
            ])->dispatch($TA);
        })->dailyAt('21:42');

        $schedule->job(new RekapDataBarangDBR())->dailyAt('20:54');

       $schedule->call(function (){
          UpdateStatusHenti::withChain([
              new UpdateStatusUsul(),
              new UpdateStatusHapus()
          ])->dispatch();
       })->monthlyOn();

        //$schedule->job(new UpdateRegisterAset())->dailyAt('09:01');
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
