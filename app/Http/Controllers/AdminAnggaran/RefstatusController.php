<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\AdminAnggaran\RefStatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RefstatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function refstatus(){
        $judul = "List RefStatus";
        return view('AdminAnggaran.refstatus',[
            "judul"=>$judul
        ]);
    }

    public function getListRefstatus(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = RefStatusModel::where([
                ['tahunanggaran','=',$tahunanggaran],
                ['kd_sts_history','LIKE','B%']
            ])->orwhere([
                ['kd_sts_history','LIKE','C%'],
                ['tahunanggaran','=',$tahunanggaran],
                ['flag_update_coa','=',1]
            ])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->statusimport == 1){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->kdsatker."/".$row->kd_sts_history.'" data-original-title="importanggaran" class="edit btn btn-primary btn-sm importanggaran">Import</a>';
                    }else{
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->kdsatker."/".$row->kd_sts_history.'" data-original-title="importanggaran" class="btn btn-primary btn-sm importanggaran">Import</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="rekapanggaran" class="btn btn-success btn-sm rekapanggaran">Rekap</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="exportanggaran" class="btn btn-success btn-sm exportanggaran">Export</a>';
                    }


                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function importrefstatus(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ANG';
        $tipedata = 'refSts';

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);
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
                        $ID = $DATA->ID;
                        $KODE_KEMENTERIAN = $DATA->KODE_KEMENTERIAN;
                        $KDSATKER = $DATA->KDSATKER;
                        $KODE_STS_HISTORY = $DATA->KODE_STS_HISTORY;
                        $JENIS_REVISI = $DATA->JENIS_REVISI;
                        $REVISIKE = $DATA->REVISI_KE;
                        $PAGU_BELANJA = $DATA->PAGU_BELANJA;
                        $NO_DIPA = $DATA->NO_DIPA;
                        $TGL_DIPA = $DATA->TGL_DIPA;
                        $TGL_DIPA = new \DateTime($TGL_DIPA);
                        $TGL_DIPA = $TGL_DIPA->format('Y-m-d');
                        $TGL_REVISI = new \DateTime($DATA->TGL_REVISI);
                        $TGL_REVISI = $TGL_REVISI->format('Y-m-d');
                        $APPROVE = $DATA->APPROVE;
                        $APPROVE_SPAN = $DATA->APPROVE_SPAN;
                        $VALIDATED = $DATA->VALIDATED;
                        $FLAG_UPDATE_COA = $DATA->FLAG_UPDATE_COA;
                        $OWNER = $DATA->OWNER;
                        $DIGITAL_STAMP = $DATA->DIGITAL_STAMP;

                        $statusimport = DB::table('data_ang')->where('idrefstatus','=',$ID)->count();
                        if ($statusimport >0){
                            $statusimport = 1;
                        }else{
                            $statusimport = 2;
                        }

                        $data = array(
                            'idrefstatus' => $ID,
                            'tahunanggaran' => session('tahunanggaran'),
                            'kode_kementerian' => $KODE_KEMENTERIAN,
                            'kdsatker' => $KDSATKER,
                            'kd_sts_history' => $KODE_STS_HISTORY,
                            'jenis_revisi' => $JENIS_REVISI,
                            'revisi_ke' => $REVISIKE,
                            'pagu_belanja' => $PAGU_BELANJA,
                            'no_dipa' => $NO_DIPA,
                            'tgl_dipa' => $TGL_DIPA,
                            'tgl_revisi' => $TGL_REVISI,
                            'approve' => $APPROVE,
                            'approve_span' => $APPROVE_SPAN,
                            'validated' => $VALIDATED,
                            'flag_update_coa' => $FLAG_UPDATE_COA,
                            'owner' => $OWNER,
                            'digital_stamp' => $DIGITAL_STAMP,
                            'statusimport' => $statusimport
                        );
                        RefStatusModel::updateOrCreate(['idrefstatus' => $ID],$data);
                    }
                }
            }
            return redirect()->to('refstatus')->with('status','Import Refstatus Berhasil');
        }else if ($response == "Expired"){
                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('refstatus')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('refstatus')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
