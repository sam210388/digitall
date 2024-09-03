<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Exports\ExportAnggaran;
use App\Jobs\ImportRefStatus;
use App\Jobs\UpdateStatusAktifAnggaran;
use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\AdminAnggaran\RefStatusModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
            ]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->statusimport == 1){
                        $btn = '<div class="btn-group" role="group">';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="importanggaran" class="edit btn btn-primary btn-sm importanggaran">Import</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="rekonanggaran" class="edit btn btn-info btn-sm rekonanggaran">Rekon</a>';
                    }else{
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="importanggaran" class="btn btn-primary btn-sm importanggaran">Import</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="exportanggaran" class="btn btn-success btn-sm exportanggaran">Export</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrefstatus.'" data-original-title="rekonanggaran" class="edit btn btn-info btn-sm rekonanggaran">Rekon</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updatestatusaktif(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new UpdateStatusAktifAnggaran($tahunanggaran));
        return redirect()->to('refstatus')->with('updatestatusaktif','Update Status Aktif Anggaran Berhasil Dilakukan');
    }

    function importrefstatus(){
        $tahunanggaran = session('tahunanggaran');
        //$this->dispatch(new ImportRefStatus($tahunanggaran));
        $this->aksiimportrefstatus($tahunanggaran);

        /*
        ImportRefStatus::withChain([
            new UpdateStatusImportRefStatus($tahunanggaran),
            new ImportDataAng($tahunanggaran),
            new RekapAnggaran($tahunanggaran),
            new UpdateStatusAktifAnggaran($tahunanggaran),
            new UpdateStatusImportRefStatus($tahunanggaran)
        ])->dispatch($tahunanggaran);
        */
        return redirect()->to('refstatus')->with('status','Import Ref Status dari SAKTI Berhasil');
    }

    function exportanggaran($refstatus){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportAnggaran($refstatus),'EksportAnggaran.xlsx');
    }

    function aksiimportrefstatus($tahunanggaran){
        $kodemodul = 'ANG';
        $tipedata = 'refSts';

        //reset api dlu
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);

        if ($response != "Gagal" && $response != "Expired"){
            $hasilasli = json_decode($response);

            foreach ($hasilasli as $subArray) {
                foreach ($subArray as $item) {
                    if (isset($item->TOKEN)) {
                        $tokenresponse = $item->TOKEN;
                        $token = new BearerKey();
                        $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                    } else {
                        $ID = $item->ID;
                        $KODE_KEMENTERIAN = $item->KODE_KEMENTERIAN;
                        $KDSATKER = $item->KDSATKER;
                        $KODE_STS_HISTORY = $item->KODE_STS_HISTORY;
                        $JENIS_REVISI = $item->JENIS_REVISI;
                        $REVISIKE = $item->REVISI_KE;
                        $PAGU_BELANJA = $item->PAGU_BELANJA;
                        $NO_DIPA = $item->NO_DIPA;
                        $TGL_DIPA = $item->TGL_DIPA ? (new \DateTime($item->TGL_DIPA))->format('Y-m-d') : null;
                        $TGL_REVISI = $item->TGL_REVISI ? (new \DateTime($item->TGL_REVISI))->format('Y-m-d') : null;
                        $APPROVE = $item->APPROVE;
                        $APPROVE_SPAN = $item->APPROVE_SPAN;
                        $VALIDATED = $item->VALIDATED;
                        $FLAG_UPDATE_COA = $item->FLAG_UPDATE_COA;
                        $OWNER = $item->OWNER;
                        $DIGITAL_STAMP = $item->DIGITAL_STAMP;

                        $data = array(
                            'idrefstatus' => $ID,
                            'tahunanggaran' => $tahunanggaran,
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
                            'statusimport' => 1
                        );

                        RefStatusModel::updateOrCreate(['idrefstatus' => $ID], $data);
                    }
                }
            }
        }
    }
}
