<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\JadwalTutupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JadwalTutupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Jadwal Tutup';
        $databulan = DB::table('bulan')->get();
        if ($request->ajax()) {
            $data = JadwalTutupModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->indexjadwal.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editjadwal">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->indexjadwal.'" data-original-title="Delete" class="btn btn-danger btn-sm deletejadwal">Delete</a>';
                    return $btn;
                })
                ->addColumn('jenislaporan',function ($row){
                    $jenislaporan = $row->jenislaporan;
                    if ($jenislaporan == 1){
                        $uraianlaporan = "Laporan Tingkat Bagian";
                    }else{
                        $uraianlaporan = "Laporan Tingkat Biro";
                    }
                    return $uraianlaporan;
                })
                ->addColumn('idbulan',function ($row){
                    $idbulan = $row->idbulan;
                    $uraianbulan = DB::table('bulan')->where('id','=',$idbulan)->value('bulan');
                    return $uraianbulan;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.jadwaltutup',[
            "judul"=>$judul,
            "databulan" => $databulan
        ]);
    }

    public function store(Request $request)
    {
        //untuk jenis laporan, 1 tingkat bagian, 2 tingkat biro

        $validated = $request->validate([
            'idbulan' => 'required',
            'jadwalbuka' => 'required|date|after_or_equal:today',
            'jadwaltutup' => 'required|date|after_or_equal:jadwalbuka',
            'jenislaporan' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        $idbulan = $request->get('idbulan');
        $jenislaporan= $request->get('jenislaporan');
        $indexjadwal = $jenislaporan.$tahunanggaran.$idbulan;
        JadwalTutupModel::create(
            [
                'tahunanggaran' => $tahunanggaran,
                'idbulan' => $idbulan,
                'jadwalbuka' => $request->get('jadwalbuka'),
                'jadwaltutup' => $request->get('jadwaltutup'),
                'jenislaporan' => $jenislaporan,
                'indexjadwal' => $indexjadwal
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    public function edit($indexjadwal)
    {
        $menu = JadwalTutupModel::where('indexjadwal',$indexjadwal)->get();
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
            'idbulan' => 'required',
            'jadwalbuka' => 'required|date|after_or_equal:today',
            'jadwaltutup' => 'required|date|after_or_equal:jadwalbuka',
            'jenislaporan' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        $idbulan = $request->get('idbulan');
        $jenislaporan= $request->get('jenislaporan');
        $indexjadwal = $jenislaporan.$tahunanggaran.$idbulan;

        JadwalTutupModel::where('indexjadwal',$indexjadwal)->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'idbulan' => $idbulan,
                'jadwalbuka' => $request->get('jadwalbuka'),
                'jadwaltutup' => $request->get('jadwaltutup'),
                'jenislaporan' => $jenislaporan,
                'indexjadwal' => $indexjadwal
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    public function destroy($indexjadwal)
    {
        JadwalTutupModel::where('indexjadwal',$indexjadwal)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
