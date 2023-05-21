<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateStatusPengeluaran implements ShouldQueue
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
            ->where('THN_ANG','=',$tahunanggaran)
            ->get();
        foreach ($dataspp as $data){
            $ID_SPP = $data->ID_SPP;
            $NILAI_SP2D = $data->NILAI_SP2D;

            //cek apakah ada datapengeluarannya
            $adadata = DB::table('spppengeluaran')
                ->where('ID_SPP','=',$ID_SPP)
                ->count();
            if ($adadata >0){
                //cek apakah nilai Pengeluaran dan Nilai Potongan dah sama
                $nilaipengeluaran = DB::table('spppengeluaran')
                    ->where('ID_SPP','=',$ID_SPP)
                    ->sum('NILAI_AKUN_PENGELUARAN');

                $nilaipotongan = DB::table('spppotongan')
                    ->where('ID_SPP','=',$ID_SPP)
                    ->sum('NILAI_AKUN_POT');
                if ($NILAI_SP2D == ($nilaipengeluaran - $nilaipotongan)){
                    $dataupdate = array(
                        'STATUS_PENGELUARAN' => 2,
                        'STATUS_POTONGAN' => 2,
                        'REKON_SP2d' => 'SAMA'
                    );
                }else{
                    $dataupdate = array(
                        'STATUS_PENGELUARAN' => 1,
                        'STATUS_POTONGAN' => 1,
                        'REKON_SP2d' => 'BEDA'
                    );
                }
                DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->update($dataupdate);
            }
        }
    }
}
