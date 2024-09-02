<?php

namespace App\Jobs;

use App\Http\Controllers\Realisasi\Admin\SppPengeluaranController;
use App\Http\Controllers\Realisasi\Admin\SppPotonganController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportCOA implements ShouldQueue
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

        $dataspp = DB::table('sppheader')
            ->whereRaw('RIGHT(STS_DATA,2) NOT IN (01,02,03)')
            ->where('THN_ANG','=',$tahunanggaran)
            ->where('REKON_SP2D','=','BEDA')
            ->get();
        foreach ($dataspp as $data){
            $ID_SPP = $data->ID_SPP;

            //download spp pengeluarannya
            $spppengeluaran = new SppPengeluaranController();
            $spppengeluaran = $spppengeluaran->importspppengeluaran($ID_SPP, $tahunanggaran);

            //download spp potongannya
            $spppotongan = new SppPotonganController();
            $spppotongan = $spppotongan->importspppotongan($ID_SPP, $tahunanggaran);

        }
    }
}
