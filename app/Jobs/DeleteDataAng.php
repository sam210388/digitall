<?php

namespace App\Jobs;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteDataAng implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $idrefstatus;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idrefstatus)
    {
        $this->idrefstatus = $idrefstatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $idrefstatus = $this->idrefstatus;
        $tarikdataanggaran = new DataAngController();
        $tarikdataanggaran = $tarikdataanggaran->aksideleteanggaran($idrefstatus);
    }
}
