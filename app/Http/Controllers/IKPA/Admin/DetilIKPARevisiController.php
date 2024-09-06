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
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $databiro = BiroModel::where('status','=','on')->get();
        return view('IKPA.Admin.detilikparevisi',[
            "judul"=>$judul,
            "databiro" => $databiro
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
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editdata">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedata">Delete</a>';
                    return $btn;
                })
                ->addColumn('bagian', function (DetilRevisiModel $id) {
                    return $id->idbagian ? $id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (DetilRevisiModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro','action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdsatker' => 'required',
            'idbiro' => 'required',
            'nosurat' => 'required',
            'tanggalsurat' => 'required',
            'perihal' => 'required',
            'norevisi' => 'required',
            'tanggalpengesahan' => 'required',
            'kewenanganrevisi' => 'required',
            'status' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        $kdsatker = $validated['kdsatker'];
        $idbiro = $validated['idbiro'];
        $idbagian = $request->get('idbagian');
        $nosurat = $validated['nosurat'];
        $tanggalsurat = $validated['tanggalsurat'];
        $perihal = $validated['perihal'];
        $norevisi = $validated['norevisi'];
        $tanggalpengesahan = $validated['tanggalpengesahan'];
        $periodepengesahan = date('n',strtotime($tanggalpengesahan));
        $kewenanganrevisi = $validated['kewenanganrevisi'];
        $status = $request->get('status');

        //cek apakah sudah pernah disimpan
        $jumlah = DB::table('ikpadetilrevisi')
            ->where('kodesatker','=',$kdsatker)
            ->where('idbiro','=',$idbiro)
            ->where('idbagian','=',$idbagian)
            ->where('nosurat','=',$nosurat)
            ->count();
        if ($jumlah == 0){
            DetilRevisiModel::create(
                [
                    'tahunanggaran' => $tahunanggaran,
                    'kodesatker' => $kdsatker,
                    'idbiro' => $idbiro,
                    'idbagian' => $idbagian,
                    'nosurat' => $nosurat,
                    'tanggalsurat' => $tanggalsurat,
                    'perihal' => $perihal,
                    'norevisi' => $norevisi,
                    'tanggalpengesahan' => $tanggalpengesahan,
                    'bulanpengesahan' => $periodepengesahan,
                    'kewenanganrevisi' => $kewenanganrevisi,
                    'status' => $status
                ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }


    public function edit($id)
    {
        $menu = DetilRevisiModel::find($id);
        return response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kdsatker' => 'required',
            'idbiro' => 'required',
            'idbagian' => 'required',
            'nosurat' => 'required',
            'tanggalsurat' => 'required',
            'perihal' => 'required',
            'norevisi' => 'required',
            'tanggalpengesahan' => 'required',
            'kewenanganrevisi' => 'required',
            'status' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        $kdsatker = $request->get('kdsatker');
        $idbiro = $request->get('idbiro');
        $idbagian = $request->get('idbagian');
        $nosurat = $request->get('nosurat');
        $tanggalsurat = $request->get('tanggalsurat');
        $perihal = $request->get('perihal');
        $norevisi = $request->get('norevisi');
        $tanggalpengesahan = $request->get('tanggalpengesahan');
        $periodepengesahan = date('n',strtotime($tanggalpengesahan));
        $kewenanganrevisi = $request->get('kewenanganrevisi');
        $status = $request->get('status');

        //cek apakah sudah pernah disimpan
            DetilRevisiModel::where('id','=',$id)->update([
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kdsatker,
                'idbiro' => $idbiro,
                'idbagian' => $idbagian,
                'nosurat' => $nosurat,
                'tanggalsurat' => $tanggalsurat,
                'perihal' => $perihal,
                'norevisi' => $norevisi,
                'tanggalpengesahan' => $tanggalpengesahan,
                'bulanpengesahan' => $periodepengesahan,
                'kewenanganrevisi' => $kewenanganrevisi,
                'status' => $status
            ]);
            return response()->json(['status'=>'berhasil']);
    }

    public function destroy($id)
    {
        DetilRevisiModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);

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
