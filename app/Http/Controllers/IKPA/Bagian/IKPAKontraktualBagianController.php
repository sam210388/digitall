<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPAKontraktualModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class IKPAKontraktualBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Kontraktual';
        return view('IKPA.Bagian.ikpakontraktual',[
            "judul"=>$judul,
        ]);
    }

    public function getdataikpakontraktualbagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =IKPAKontraktualModel::with('bagianrelation')
                ->select(['ikpakontraktualbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->orderBy('kodesatker','asc')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPAKontraktualModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->rawColumns(['bagian'])
                ->make(true);
        }
    }
}
