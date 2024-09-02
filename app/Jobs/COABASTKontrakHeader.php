<?php

namespace App\Jobs;

use App\Http\Controllers\Realisasi\Admin\BASTKontrakHeaderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class COABASTKontrakHeader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $bastkontrakheader = new BASTKontrakHeaderController();
        $bastkontrakheader = $bastkontrakheader->aksiimportbastcoakontraktual($tahunanggaran, $kdsatker);
    }
}
