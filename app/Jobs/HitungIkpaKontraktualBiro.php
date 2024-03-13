<?php

namespace App\Jobs;

use App\Http\Controllers\IKPA\Admin\IKPAKontraktualController;
use App\Http\Controllers\IKPA\Admin\IKPAPenyerapanBagianController;
use App\Http\Controllers\IKPA\Bagian\IKPAPenyerapanBagianController;
use App\Http\Controllers\Realisasi\Admin\RealisasiSaktiController;
use App\Http\Controllers\Realisasi\Admin\SppHeaderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HitungIkpaKontraktualBiro implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tahunanggaran;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahunanggaran)
    {
        $this->tahunanggaran = $tahunanggaran;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tahunanggaran = $this->tahunanggaran;
        $sppheader = new IKPAKontraktualController();
        $sppheader = $sppheader->aksiperhitunganikpakontraktualbiro($tahunanggaran);
    }
}
