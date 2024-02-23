<?php

namespace App\Jobs;

use App\Http\Controllers\Realisasi\Admin\KontrakHeaderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportKontrakHeader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tahunanggaran;
    protected $kdsatker;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahunanggaran, $kdsatker)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->kdsatker = $kdsatker;
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
        $importkontrakheader = new KontrakHeaderController();
        $importkontrakheader = $importkontrakheader->aksiimportkontrakheader($tahunanggaran, $kdsatker);

    }
}
