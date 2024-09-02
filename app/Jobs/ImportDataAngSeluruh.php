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

class ImportDataAngSeluruh implements ShouldQueue
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
        $dataidrefstatus = DB::table('ref_status')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('statusimport','=',1)->get();
        foreach ($dataidrefstatus as $data){
            $idrefstatus = $data->idrefstatus;
            //delete data idrefsgtatus sebelumnya
            DB::table('data_ang')->where('idrefstatus','=',$idrefstatus)->delete();

            //tarik data anggarannya
            $tarikdataanggaran = new DataAngController();
            $tarikdataanggaran = $tarikdataanggaran->aksiimportdataang($tahunanggaran, $idrefstatus);
        }
    }
}
