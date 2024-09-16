<?php

namespace App\Http\Controllers\IKPA\Bagian;


use App\Exports\ExportIkpaKontraktualBiro;
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

class IKPARevisiBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Revisi Bagian';
        $idbagian = Auth::user()->idbagian;
        return view('IKPA.bagian.ikparevisibagian',[
            "judul"=>$judul,
            "idbagian" => $idbagian,
        ]);
    }



    function exportikparevisibagian(){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIKPARevisiBagian($tahunanggaran, $idbagian),'IKPARevisiBagian.xlsx');
    }

    public function getdataikparevisibagian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPARevisiBagianModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikparevisibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPARevisiBagianModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPARevisiBagianModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }
}
