<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAPenyerapanBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Penyerapan';
        return view('IKPA.Bagian.ikpapenyerapan',[
            "judul"=>$judul
        ]);
    }

    public function getdataikpapenyerapanbagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =IkpaPenyerapanModel::with('bagianrelation')
                ->select(['ikpapenyerapanbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian);
            return Datatables::of($data)
                ->addColumn('bagian', function (IkpaPenyerapanModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->rawColumns(['bagian'])
                ->make(true);
        }
    }

}
