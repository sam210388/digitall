<?php

namespace App\Jobs;

use App\Http\Controllers\GL\BukuBesarController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportBukuBesar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tahunanggaran;
    protected $kdsatker;
    protected $periode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahunanggaran, $kdsatker, $periode)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->kdsatker = $kdsatker;
        $this->periode = $periode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tahunanggaran = $this->tahunanggaran;
        $kdsatker = $this->kdsatker;
        $periode = $this->periode;
        $sppheader = new BukuBesarController();
        $sppheader = $sppheader->aksiimportbukubesar($tahunanggaran, $kdsatker, $periode);
    }
}
