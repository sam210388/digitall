<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPADeviasiBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        return view('IKPA.Bagian.ikpadeviasi',[
            "judul"=>$judul
        ]);
    }

    public function getdataikpadeviasi(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =IKPADeviasiModel::with('bagianrelation')
                ->select(['ikpadeviasibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->orderBy('kdsatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPADeviasiModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->rawColumns(['bagian'])
                ->make(true);
        }
    }
}
