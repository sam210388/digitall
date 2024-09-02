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

class RekapAnggaran implements ShouldQueue
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
        $dataanggaran = DB::table('data_ang')
            ->select('idrefstatus')
            ->where('active','=',2)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->distinct()
            ->get();
        foreach ($dataanggaran as $data){
            $idrefstatus = $data->idrefstatus;
            $rekapanggaran = new DataAngController();
            $rekapanggaran = $rekapanggaran->rekapanggarannoredirect($idrefstatus, $tahunanggaran);
        }

    }
}
