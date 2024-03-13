<?php

namespace App\Jobs;

use App\Http\Controllers\IKPA\Admin\IKPADeviasiBagianController;
use App\Http\Controllers\IKPA\Admin\IKPAPenyelesaianTagihanBagianController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HitungIkpaPenyelesaianBagian implements ShouldQueue
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
        $sppheader = new IKPAPenyelesaianTagihanBagianController();
        $sppheader = $sppheader->aksiperhitunganpenyelesaianbagian($tahunanggaran);
    }
}
