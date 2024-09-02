<?php

namespace App\Jobs;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportDataAng implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $idrefstatus;
    protected $tahunanggaran;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahunanggaran, $idrefstatus)
    {
        $this->idrefstatus = $idrefstatus;
        $this->tahunanggaran = $tahunanggaran;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $idrefstatus = $this->idrefstatus;
        $tahunanggaran = $this->tahunanggaran;
        $tarikdataanggaran = new DataAngController();
        $tarikdataanggaran = $tarikdataanggaran->aksiimportdataang($tahunanggaran, $idrefstatus);
    }
}
