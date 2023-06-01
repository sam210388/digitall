<?php

namespace App\Jobs;

use App\Models\AdminAnggaran\RefStatusModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateStatusAktifAnggaran implements ShouldQueue
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
        DB::table('data_ang')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->update([
                'active' => 1
            ]);
        $satker = array('001012', '001030');
        $datatanggaltutup = DB::table('jadwaltutupcashplan')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('jenisdata','=','HAL3DIPA')
            ->orderBy('periode','asc')
            ->pluck('tanggaltutup');
        //echo $tanggaltutup3 = $datatanggaltutup[3];
        foreach ($satker as $item) {
            $kdsatker = $item;
            $idrefstatusterakhir = DB::table('ref_status')
                ->where([
                    ['kdsatker', '=', $kdsatker],
                    ['tahunanggaran', '=', $tahunanggaran],
                    ['kd_sts_history', 'LIKE', 'B%']
                ])->orwhere([
                    ['kdsatker', '=', $kdsatker],
                    ['kd_sts_history', 'LIKE', 'C%'],
                    ['tahunanggaran', '=', $tahunanggaran],
                    ['flag_update_coa', '=', 1]
                ])->max('idrefstatus');
            DB::table('data_ang')->where('idrefstatus', '=', $idrefstatusterakhir)->update(['active' => 2]);

            for($i=0; $i<4;$i++){
                $tanggaltutup = $datatanggaltutup[$i];
                $idrefstatustw = DB::table('ref_status')
                    ->where([
                        ['kdsatker','=',$kdsatker],
                        ['tahunanggaran','=',$tahunanggaran],
                        ['kd_sts_history','LIKE','B%'],
                        ['tgl_revisi','<=',$tanggaltutup]
                    ])->orwhere([
                        ['kdsatker','=',$kdsatker],
                        ['kd_sts_history','LIKE','C%'],
                        ['tahunanggaran','=',$tahunanggaran],
                        ['flag_update_coa','=',1],
                        ['tgl_revisi','<=',$tanggaltutup]
                    ])->max('idrefstatus');
                $datawhere = array(
                    'kodesatker' => $kdsatker,
                    'tahunanggaran' => $tahunanggaran,
                    'triwulan' => $i+1
                );
                $cekdata = DB::table('refstatuscashplan')->where($datawhere)->count();
                if ($cekdata == 0){
                    $datainsert = array(
                        'kodesatker' => $kdsatker,
                        'tahunanggaran' => $tahunanggaran,
                        'triwulan' => $i+1,
                        'idrefstatus' => $idrefstatustw
                    );
                    DB::table('refstatuscashplan')->insert($datainsert);
                }else{
                    DB::table('refstatuscashplan')->where($datawhere)->update(['idrefstatus' => $idrefstatustw]);
                }
            }
        }
    }
}
