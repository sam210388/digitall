<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPADeviasiBiroModel;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPADeviasiAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        return view('IKPA.Biro.ikpadeviasi',[
            "judul"=>$judul
        ]);
    }

    public function getdataikpadeviasi(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =IKPADeviasiBiroModel::select(['ikpadeviasibiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('ikpadeviasibiro.idbiro','=',$idbiro)
                ->orderBy('kdsatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->make(true);
        }
    }
}
