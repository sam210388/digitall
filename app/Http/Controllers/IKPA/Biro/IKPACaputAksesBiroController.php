<?php

namespace App\Http\Controllers\IKPA\Biro;


use App\Exports\ExportIkpaCaputBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaCaputBiro;
use App\Models\IKPA\Admin\IKPACaputBiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPACaputAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Capaian Output Biro';
        $idbiro = Auth::user()->idbiro;
        return view('IKPA.biro.ikpacaputbiro',[
            "judul"=>$judul,
            "idbiro" => $idbiro,
        ]);
    }


    public function getdataikpacaput(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPACaputBiroModel::with('birorelation')
                ->select(['ikpacaputbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPACaputBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    function exportikpacaputbiro(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaCaputBiro($tahunanggaran),'IKPACaputBiro.xlsx');
    }
}
