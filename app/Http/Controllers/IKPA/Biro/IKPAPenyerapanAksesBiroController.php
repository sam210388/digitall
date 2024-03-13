<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyerapanBiroModel;
use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAPenyerapanAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Penyerapan';
        return view('IKPA.Biro.ikpapenyerapan',[
            "judul"=>$judul
        ]);
    }

    public function getdataikpapenyerapanbagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =IkpaPenyerapanBiroModel::select(['ikpapenyerapanbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbiro','=',$idbiro);
            return Datatables::of($data)
                ->make(true);
        }
    }

}
