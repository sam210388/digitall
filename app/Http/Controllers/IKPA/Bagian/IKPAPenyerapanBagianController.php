<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use App\Models\IKPA\IkpaPenyerapanBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Switch_;
use Yajra\DataTables\DataTables;

class IKPAPenyerapanBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Penyerapan';
        return view('IKPA.Bagian.ikpapenyerapanbagian',[
            "judul"=>$judul,

        ]);
    }

    public function getdataikpapenyerapanbagian(Request $request){
        $idbagian = Auth::user()->idbagian;
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyerapanModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpapenyerapanbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian', '=', $idbagian);

            return Datatables::of($data)
                ->addColumn('bagian', function (IkpaPenyerapanModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IkpaPenyerapanModel $id) {
                    return $id->birorelation->uraianbiro;
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }
}
