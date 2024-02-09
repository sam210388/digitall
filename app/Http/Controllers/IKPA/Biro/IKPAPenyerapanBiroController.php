<?php

namespace App\Http\Controllers\IKPA\Biro;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use App\Models\IKPA\IkpaPenyerapanBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Switch_;
use Yajra\DataTables\DataTables;

class IKPAPenyerapanBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $idbiro = Auth::user()->idbiro;
        $judul = 'Penilaian IKPA Penyerapan';
        $databagian = DB::table('bagian')
            ->where('status','=','on')
            ->where('idbiro','=',$idbiro)
            ->get();
        return view('IKPA.Admin.ikpapenyerapan',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function getdataikpapenyerapanbagian(Request $request, $idbagian){
        $idbiro = Auth::user()->idbiro;
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyerapanModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpapenyerapanbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbiro', '=', $idbiro);
            if ($idbagian != 0) {
                $data->where('idbagian', '=', $idbagian);
            }

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
