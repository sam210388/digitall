<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAPenyelesaianTagihanBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Penyelesaian Tagihan';
        return view('IKPA.Bagian.ikpapenyelesaian',[
            "judul"=>$judul,
        ]);
    }

    public function getdataikpapenyelesaian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =IkpaPenyelesaianTagihan::with('bagianrelation')
                ->select(['ikpapenyelesaiantagihan.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->orderBy('kdsatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (IkpaPenyelesaianTagihan $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->rawColumns(['bagian'])
                ->make(true);
        }
    }
}
