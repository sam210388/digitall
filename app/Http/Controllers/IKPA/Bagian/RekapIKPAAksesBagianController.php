<?php

namespace App\Http\Controllers\IKPA\Bagian;

use App\Exports\ExportIkpaKontraktualBagian;
use App\Exports\ExportIkpaPenyelesaianBagian;
use App\Exports\ExportRekapIKPABagian;
use App\Exports\ExportRekapIKPABiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaKontraktualBagian;
use App\Jobs\HitungIkpaPenyelesaianBagian;
use App\Jobs\HitungRekapIKPABagian;
use App\Jobs\HitungRekapIKPABiro;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use App\Models\IKPA\Admin\IKPAKontraktualModel;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use App\Models\IKPA\Admin\RekapIKPABagianlModel;
use App\Models\IKPA\Admin\RekapIKPABiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RekapIKPAAksesBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Rekap IKPA Bagian';
        return view('IKPA.Bagian.rekapikpabagian',[
            "judul"=>$judul,
        ]);
    }

    public function getdatarekapikpabagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data =RekapIKPABagianlModel::with('bagianrelation')
                ->select(['ikparekapbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->orderBy('kodesatker','asc')
                ->orderBy('periode','asc');

            return Datatables::of($data)
                ->addColumn('bagian', function (RekapIKPABagianlModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->rawColumns(['bagian'])
                ->make(true);
        }
    }
}
