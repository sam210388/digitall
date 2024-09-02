<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Realisasi\Admin\KontrakCOAController;
use App\Imports\DetilKontraktualImport;
use App\Imports\PenyelesaianTagihanImport;
use App\Jobs\ImportKontrakCOA;
use App\Jobs\ImportKontrakHeader;
use App\Models\IKPA\Admin\DetilKontraktualModel;
use App\Models\IKPA\Admin\DetilPenyelesaianTagihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DetilIKPAKontraktualController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Detil IKPA Kontraktual';
        return view('IKPA.Admin.detilikpakontraktual',[
            "judul"=>$judul
        ]);
    }

    public function getdetilkontraktual(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =DetilKontraktualModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpadetilkontraktual.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (DetilKontraktualModel $id) {
                    return $id->idbagian ? $id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (DetilKontraktualModel $id) {
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

        $import = Excel::import(new DetilKontraktualImport(), storage_path('app/public/excel/'.$namafile));

        Storage::delete($path);

        //return redirect()->to('detilpenyelesaiantagihan')->with('status','Import Data Detail Berhasil');
        if($import) {
            //return response()->json(['status'=>'berhasil']);
            return redirect()->to('detilikpakontraktual')->with(['status' => 'Data Berhasil Diimport']);
        }else{
            //return response()->json(['status'=>'gagal']);
            return redirect()->to('detilikpakontraktual')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }

    }

    public function importkontrakcoa(){
        $tahunanggaran = session('tahunanggaran');
        ImportKontrakCOA::withChain([
            new ImportKontrakHeader($tahunanggaran)
        ])->dispatch($tahunanggaran);
        return redirect()->to('detilikpakontraktual')->with(['status' => 'Data Berhasil Diimport']);
    }
}
