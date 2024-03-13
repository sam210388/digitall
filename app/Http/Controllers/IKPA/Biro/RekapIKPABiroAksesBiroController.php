<?php

namespace App\Http\Controllers\IKPA\Biro;

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

class RekapIKPABiroAksesBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Rekap IKPA Biro';
        return view('IKPA.Biro.rekapikpabiro',[
            "judul"=>$judul,
        ]);
    }

    public function getdatarekapikpabagian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data =RekapIKPABiroModel::select(['ikparekapbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbiro','=',$idbiro)
                ->orderBy('kodesatker','asc')
                ->orderBy('periode','asc');

            return Datatables::of($data)
                ->make(true);
        }
    }
}
