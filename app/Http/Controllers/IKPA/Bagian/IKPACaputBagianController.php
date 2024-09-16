<?php

namespace App\Http\Controllers\IKPA\Bagian;


use App\Exports\ExportIkpaCaputBagian;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaCaputBagian;
use App\Models\IKPA\Admin\IKPADetailCaputModel;
use App\Models\IKPA\Admin\IKPACaputBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPACaputBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Capaian Output';
        $idbagian = Auth::user()->idbagian;
        return view('IKPA.bagian.ikpacaputbagian',[
            "judul"=>$judul,
            "idbagian"=>$idbagian,
        ]);
    }

    function exportikpacaputbagian(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaCaputBagian($tahunanggaran),'IKPACaputBagian.xlsx');
    }


    public function getdataikpacaput(Request $request, $idbagian){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPACaputBagianModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpacaputbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPACaputBagianModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPACaputBagianModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }
}
