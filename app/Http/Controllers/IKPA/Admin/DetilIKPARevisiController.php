<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Realisasi\Admin\KontrakCOAController;
use App\Imports\DetilKontraktualImport;
use App\Imports\DetilRevisiImport;
use App\Imports\PenyelesaianTagihanImport;
use App\Jobs\ImportKontrakCOA;
use App\Jobs\ImportKontrakHeader;
use App\Models\IKPA\Admin\DetilKontraktualModel;
use App\Models\IKPA\Admin\DetilPenyelesaianTagihanModel;
use App\Models\IKPA\Admin\DetilRevisiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DetilIKPARevisiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Detil IKPA Revisi';
        return view('IKPA.Admin.detilikparevisi',[
            "judul"=>$judul
        ]);
    }

    public function getdetilrevisi(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =DetilRevisiModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpadetilrevisi.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian');
            return Datatables::of($data)
                ->addColumn('bagian', function (DetilRevisiModel $id) {
                    return $id->idbagian ? $id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (DetilRevisiModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function importdata(Request $request){
        $request->validate([
            'filedetail' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('filedetail');
        //echo $file;
        $namafile = $file->hashName();

        $path = $file->storeAs('public/excel/',$namafile);

        //echo $path;

        $import = Excel::import(new DetilRevisiImport(), storage_path('app/public/excel/'.$namafile));

        Storage::delete($path);

        //return redirect()->to('detilpenyelesaiantagihan')->with('status','Import Data Detail Berhasil');
        if($import) {
            //return response()->json(['status'=>'berhasil']);
            return redirect()->to('ikpadetilrevisi')->with(['status' => 'Data Berhasil Diimport']);
        }else{
            //return response()->json(['status'=>'gagal']);
            return redirect()->to('ikpadetilrevisi')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }

    }
}
