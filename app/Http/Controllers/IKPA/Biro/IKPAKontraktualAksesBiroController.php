<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPAKontraktualBiroModel;
use App\Models\IKPA\Admin\IKPAKontraktualModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAKontraktualAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Kontraktual';
        return view('IKPA.Biro.ikpakontraktual',[
            "judul"=>$judul,
        ]);
    }

    public function getdataikpakontraktualbagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =IKPAKontraktualBiroModel::select(['ikpakontraktualbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('ikpakontraktualbiro.idbiro','=',$idbiro)
                ->orderBy('kodesatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->make(true);
        }
    }
}
