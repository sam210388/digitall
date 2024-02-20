<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PenyelesaianTagihanImport;
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
        return view('IKPA.Admin.detilpenyelesaiantagihan',[
            "judul"=>$judul
        ]);
    }

    public function getdetilpenyelesaian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =DetilPenyelesaianTagihanModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['detilpenyelesaiantagihanbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            return Datatables::of($data)
                ->addColumn('bagian', function (DetilPenyelesaianTagihanModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (DetilPenyelesaianTagihanModel $id) {
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

        $import = Excel::import(new PenyelesaianTagihanImport(), storage_path('app/public/excel/'.$namafile));

        Storage::delete($path);

        //return redirect()->to('detilpenyelesaiantagihan')->with('status','Import Data Detail Berhasil');
        if($import) {
            //return response()->json(['status'=>'berhasil']);
            return redirect()->to('detilpenyelesaiantagihan')->with(['status' => 'Data Berhasil Diimport']);
        }else{
            //return response()->json(['status'=>'gagal']);
            return redirect()->to('detilpenyelesaiantagihan')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }


    }
}
