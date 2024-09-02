<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihanBiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAPenyelesaianTagihanAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Penyelesaian Tagihan';
        return view('IKPA.Biro.ikpapenyelesaian',[
            "judul"=>$judul,
        ]);
    }

    public function getdataikpapenyelesaian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =IkpaPenyelesaianTagihanBiro::select(['ikpapenyelesaiantagihanbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('ikpapenyelesaiantagihanbiro.idbiro','=',$idbiro)
                ->orderBy('kdsatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->make(true);
        }
    }
}
