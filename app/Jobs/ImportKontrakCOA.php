<?php

namespace App\Jobs;

use App\Http\Controllers\Realisasi\Admin\KontrakCOAController;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportKontrakCOA implements ShouldQueue
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
        $importkontrakcoa = new KontrakCOAController();
        $importkontrakcoa = $importkontrakcoa->aksiimportkontrakcoa($tahunanggaran);
    }
}
