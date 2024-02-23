<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportKontrakCOA;
use App\Jobs\ImportKontrakHeader;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\KontrakCOAModel;
use App\Models\Realisasi\Admin\KontrakHeaderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KontrakCOAController extends Controller
{
    public function kontrakheader(Request $request)
    {
        $judul = 'Data COA Kontrak';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('kontrakcoa');
            return Datatables::of($data)
                ->make(true);
        }

        return view('Realisasi.Admin.kontrakcoa',[
            "judul"=>$judul,
        ]);
    }

    function importkontrakcoa(){
        $kodesatker = ['001012','001030'];
        $tahunanggaran = session('tahunanggaran');
        foreach ($kodesatker as $kode){
            $this->dispatch(new ImportKontrakCOA($kode, $tahunanggaran));
        }
        return redirect()->to('kontrakcoa')->with('status','Import Kontrak COA dari SAKTI Berhasil');
    }

    function aksiimportkontrakcoa($kodesatker, $tahunanggaran){
        $kodemodul = 'KOM';
        $tipedata = 'kontrakCOA';
        $variabel = [$kodesatker];

        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
            //echo json_encode($hasilasli);
            foreach ($hasilasli as $item => $value) {
                if ($item == "TOKEN") {
                    foreach ($value as $data) {
                        $tokenresponse = $data->TOKEN;
                    }
                    $token = new BearerKey();
                    $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                }else{
                    //delete dlu data coa tahun anggaran terkait
                    DB::table('kontrakCOA')->where('THNANG','=',$tahunanggaran)->delete();
                    foreach ($value as $DATA) {
                        $KODE_KEMENTERIAN = $DATA->KODE_KEMENTERIAN;
                        $KDSATKER = $DATA->KDSATKER;
                        $ID_KONTRAK = $DATA->ID_KONTRAK;
                        $ID_LINE_KONTRAK = $DATA->ID_LINE_KONTRAK;
                        $ID_JADWAL_PEMBAYARAN = $DATA->ID_JADWAL_PEMBAYARAN;
                        $KODE_PROGRAM = $DATA->KODE_PROGRAM;
                        $KODE_KEGIATAN = $DATA->KODE_KEGIATAN;
                        $KODE_OUTPUT = $DATA->KODE_OUTPUT;
                        $KODE_AKUN = $DATA->KODE_AKUN;
                        $KODE_SUBOUTPUT = $DATA->KODE_SUBOUTPUT;
                        $KODE_KOMPONEN = $DATA->KODE_KOMPONEN;
                        $KODE_SUBKOMPONEN = $DATA->KODE_SUBKOMPONEN;
                        $KODE_ITEM = $DATA->KODE_ITEM;
                        $KODE_COA = $DATA->KODE_COA;
                        $VOL_SUBOUTPUT = $DATA->VOL_SUBOUTPUT;
                        $NILAI_COA_DETAIL = $DATA->NILAI_COA_DETAIL;
                        $PENGENAL = $tahunanggaran.".".$KDSATKER.".".$KODE_PROGRAM.".".$KODE_KEGIATAN.".".$KODE_OUTPUT.".".$KODE_SUBOUTPUT.".".$KODE_KOMPONEN.".".$KODE_SUBKOMPONEN.".".$KODE_AKUN;
                        $IDBAGIAN = DB::table('laporanrealisasianggaranbac')->where('pengenal','=',$PENGENAL)->value('idbagian');
                        $IDBIRO = DB::table('laporanrealisasianggaranbac')->where('pengenal','=',$PENGENAL)->value('idbiro');
                        $data = array(
                            'THNANG' => $tahunanggaran,
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'ID_KONTRAK' => $ID_KONTRAK,
                            'ID_LINE_KONTRAK' => $ID_LINE_KONTRAK,
                            'ID_JADWAL_PEMBAYARAN' => $ID_JADWAL_PEMBAYARAN,
                            'KODE_PROGRAM' => $KODE_PROGRAM,
                            'KODE_KEGIATAN' => $KODE_KEGIATAN,
                            'KODE_OUTPUT' => $KODE_OUTPUT,
                            'KODE_AKUN' => $KODE_AKUN,
                            'KODE_SUBOUTPUT' => $KODE_SUBOUTPUT,
                            'KODE_KOMPONEN' => $KODE_KOMPONEN,
                            'KODE_SUBKOMPONEN' => $KODE_SUBKOMPONEN,
                            'KODE_ITEM' => $KODE_ITEM,
                            'KODE_COA' => $KODE_COA,
                            'VOL_SUBOUTPUT' => $VOL_SUBOUTPUT,
                            'NILAI_COA_DETAIL' => $NILAI_COA_DETAIL,
                            'pengenal' => $PENGENAL,
                            'idbagian' => $IDBAGIAN,
                            'idbiro' =>  $IDBIRO
                        );
                        DB::table('kontrakcoa')->insert($data);
                    }
                }
            }
        }
    }

}
