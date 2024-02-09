<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IKPADeviasiBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        $idbiro = Auth::user()->idbiro;
        $databagian = DB::table('bagian')
            ->where('status','=','on')
            ->where('idbiro','=',$idbiro)
            ->get();
        return view('IKPA.Admin.ikpadeviasi',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function getdataikpadeviasi(Request $request, $idbagian){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =IKPADeviasiModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpadeviasibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbiro','=',$idbiro)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != 0) {
                $data->where('idbagian', '=', $idbagian);
            }
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
