<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IKPADeviasiBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        return view('IKPA.Bagian.ikpadeviasibagian',[
            "judul"=>$judul,
        ]);
    }

    public function getdataikpadeviasi(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =IKPADeviasiModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpadeviasibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian', '=', $idbagian)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPADeviasiModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPADeviasiModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }
}
