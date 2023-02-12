<?php

namespace App\Http\Controllers\Caput\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\RealisasiRincianIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiRincianIndikatorROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'List Realisasi Rincian Indikator RO';
        $tahunanggaran = session('tahunanggaran');
        $databulan = DB::table('bulan')->get();
        $datastatuspelaksanaan = DB::table('statuspelaksanaan')->get();
        $datakategoripermasalahan = DB::table('kategoripermasalahan')->get();

        if ($request->ajax()) {
            $data = RealisasiRincianIndikatorROModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrincianindikatorro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterincianindikatorro">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.realisasirincianindikatorro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan
        ]);
    }

}
