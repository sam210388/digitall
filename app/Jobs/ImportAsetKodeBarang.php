<?php

namespace App\Jobs;

use App\Http\Controllers\Sirangga\Admin\ImportSaktiController;
use App\Http\Controllers\Sirangga\Admin\ListImportSaktiController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportAsetKodeBarang implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $kodebarang;
    protected $tahunanggaran;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kodebarang, $tahunanggaran)
    {
        $this->kodebarang = $kodebarang;
        $this->tahunanggaran = $tahunanggaran;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $kodebarang = $this->kodebarang;
        $tahunanggaraan = $this->tahunanggaran;
        $tarikdata = new ListImportSaktiController();
        $tarikdata = $tarikdata->aksiimporttransaksiaset($kodebarang, $tahunanggaraan);

    }
}
