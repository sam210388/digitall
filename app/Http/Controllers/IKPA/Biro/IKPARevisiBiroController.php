<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Exports\ExportIKPARevisiBagian;
use App\Exports\ExportIKPARevisiBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaRevisi;
use App\Jobs\HitungIkpaRevisiBiro;
use App\Models\IKPA\Admin\IKPARevisiBagianModel;
use App\Models\IKPA\Admin\IKPARevisiBiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPARevisiBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    //BIRO
    public function indexbiro(){
        $judul = 'IKPA Revisi';
        $idbiro = Auth::user()->idbiro;
        return view('IKPA.biro.ikparevisibiro',[
            "judul"=>$judul,
           "idbiro" => $idbiro,
        ]);
    }

    function exportikparevisibiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIKPARevisiBiro($tahunanggaran),'IKPARevisiBiro.xlsx');
    }

    public function getdataikparevisibiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPARevisiBiroModel::with('birorelation')
                ->select(['ikparevisibiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPARevisiBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }
}
