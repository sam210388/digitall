<?php

namespace App\Http\Controllers\Realisasi;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\CekPengenal;
use App\Libraries\FilterDataUser;
use App\Libraries\PeriodeLaporan;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiUnit\BagianModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminAnggaran\AnggaranBagianModel;
use Yajra\DataTables\DataTables;

class RealisasiSAKTIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function realisasisakti(Request $request)
    {
        $judul = 'Data Realisasi SAKTI';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $wheredata= new FilterDataUser();
            $wheredata = $wheredata->filterdata();
            if (count($wheredata)>0){
                $wheretambahan = $wheredata;
            }else{
                $wheretambahan = array();
            }
            $data = DB::table('realisasisakti')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('pengenal','!=',null)
                ->where($wheretambahan)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('idbagian',function ($row){
                    $idbagian = $row->idbagian;
                    $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbagian;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->make(true);
        }

        return view('Realisasi.realisasisakti',[
            "judul"=>$judul,
        ]);
    }

    function importdataang($kdsatker, $kd_sts_history){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ANG';
        $tipedata = 'dataAng';
        $variable = [$kdsatker, $kd_sts_history];

        //dapatkan idrefstatus
        $idrefstatus = DB::table('ref_status')
            ->where('kd_sts_history','=',$kd_sts_history)
            ->where('kdsatker','=',$kdsatker)
            ->value('idrefstatus');

        //delete data ang yang sudah diimport sebelumnya
        DB::table('data_ang')
            ->where('kdsatker','=',$kdsatker)
            ->where('idrefstatus','=',$idrefstatus)
            ->delete();

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);
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
                }
            }
            foreach ($hasilasli as $item => $value) {
                if ($item != "TOKEN") {
                    foreach ($value as $DATA) {
                        $kdsatker = $DATA->KDSATKER;
                        $kode_kementerian = $DATA->KODE_KEMENTERIAN;
                        $tgl_sp2d = new \DateTime($DATA->TGL_SP2D);
                        $tgl_sp2d = $tgl_sp2d->format('Y-m-d');
                        $no_spp = $DATA->NO_SPP;
                        $no_sp2d = $DATA->NO_SP2D;
                        $uraian = $DATA->URAIAN;
                        $kode_coa = $DATA->KODE_COA;
                        $kodeprogram = substr($kode_coa,23,2);
                        $kodekeegiatan = substr($kode_coa,26,4);
                        $kodeoutput = substr($kode_coa,30,3);
                        $kodesuboutput = substr($kode_coa,74,3);
                        $kodekomponen = substr($kode_coa,78,3);
                        $kodesubbkomponen = substr($kode_coa,83,1);
                        $kodeakun = substr($kode_coa,11,6);
                        $pengenal = $kodeprogram.'.'.$kodekeegiatan.'.'.$kodeoutput.'.'.$kodesuboutput.'.'.$kodekomponen.'.'.$kodesubbkomponen.'.'.$kodeakun;
                        $idbagian = AnggaranBagian::where('pengenal','=',$pengenal)->value('idbagian');
                        $idbiro = Bagian::where('id','=',$idbagian)->value('idbiro');
                        $iddeputi = Bagian::where('id','=',$idbagian)->value('iddeputi');
                        $mata_uang = $DATA->MATA_UANG;
                        $KURS = $DATA->KURS;
                        $nilai_valas = $DATA->NILAI_VALAS;
                        $nilai_rupiah = $DATA->NILAI_RUPIAH;

                        //001030.182.524111.00202CF.5804ABC.A000000001.00000.1.0151.2.000000.000000.001.052.0C.000000

                        $data = array(
                            'tahunanggaran' => $tahunanggaran,
                            'kdsatker' => $kdsatker,
                            'kode_kementerian' => $kode_kementerian,
                            'tgl_sp2d' => $tgl_sp2d,
                            'no_spp' => $no_spp,
                            'no_sp2d' => $no_sp2d,
                            'uraian' => $uraian,
                            'kode_coa' => $kode_coa,
                            'kodeprogram' => $kodeprogram,
                            'kodekegiatan' => $kodekeegiatan,
                            'kodeoutput' => $kodeoutput,
                            'kodesuboutput' => $kodesuboutput,
                            'kodekomponen' => $kodekomponen,
                            'kodesubkomponen' => $kodesubbkomponen,
                            'kodeakun' => $kodeakun,
                            'pengenal' => $pengenal,
                            'idbagian' => $idbagian,
                            'idbiro' => $idbiro,
                            'iddeputi' => $iddeputi,
                            'mata_uang' => $mata_uang,
                            'kurs' => $KURS,
                            'nilaivalas' => $nilai_valas,
                            'nilairupiah' => $nilai_rupiah
                        );
                    }
                }
            }
            //UBAH Status Importnya
            DB::table('ref_status')->where('idrefstatus','=',$idrefstatus)->update(['statusimport' => 2]);

            return redirect()->to('refstatus')->with('status','Import Data Anggaran Berhasil');
        }else if ($response == "Expired"){

            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            return redirect()->to('refstatus')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('refstatus')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }

}
