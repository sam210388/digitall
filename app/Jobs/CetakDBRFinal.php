<?php

namespace App\Jobs;

use App\Http\Controllers\Realisasi\Admin\SppPengeluaranController;
use App\Http\Controllers\Realisasi\Admin\SppPotonganController;
use App\Http\Controllers\Sirangga\Admin\DBRController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CetakDBRFinal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $datadbrfinal = DB::table('dbrinduk')->where('statusdbr','=',3)->get();
        foreach ($datadbrfinal as $data){
            $iddbr = $data->iddbr;
            $cetakdbr = new DBRController();
            $cetakdbr = $cetakdbr->cetakdbr($iddbr);
        }
    }
}
