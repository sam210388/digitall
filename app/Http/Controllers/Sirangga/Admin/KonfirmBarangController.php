<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Exports\ExportBarangTerkonfirmasi;
use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\BarangTerkonfirmasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class KonfirmBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function barangterkonfirmasi(){
        $totalbarangterkonfirmasi = DB::table('konfirmhilangkembali')->count();
        $baranghilang = DB::table('konfirmhilangkembali')->where('statusbarang','=',"Hilang")->count();
        $barangpengembalian = DB::table('konfirmhilangkembali')->where('statusbarang','=',"Pengembalian")->count();
        $barangbelumstatususul = DB::table('konfirmhilangkembali')->where('statususul','=',1)->count();
        $barangbelumstatushapus = DB::table('konfirmhilangkembali')->where('statushapus','=',1)->count();
        $barangbelumstatushenti = DB::table('konfirmhilangkembali')->where('statushenti','=',1)->count();
        $judul = "Data Barang Hilang / Kembali";
        return view('Sirangga.Admin.barangterkonfirmasi',[
            "judul"=>$judul,
            "totalbarangterkonfirmasi" => $totalbarangterkonfirmasi,
            "baranghilang" => $baranghilang,
            "barangpengembalian" => $barangpengembalian,
            "belumstatususul" => $barangbelumstatususul,
            "belumstatushapus" => $barangbelumstatushapus,
            "belumstatushenti" => $barangbelumstatushenti

        ]);
    }

    function exportbarangterkonfirmasi($statusbarang){
        return Excel::download(new ExportBarangTerkonfirmasi($statusbarang),'BarangTerkonfirmasi.xlsx');
    }

    public function getdatabarangterkonfirmasi()
    {
        $model = BarangTerkonfirmasiModel::select('konfirmhilangkembali.*');
        return Datatables::eloquent($model)
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group" role="group">
                       <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idbarang.'" data-original-title="KonfirmPengembalian" class="edit btn btn-danger btn-sm delete">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();

    }

    public function deletebarangterkonfirmasi(Request $request){
        $idbarang = $request->get('idbarang');
        //delete dari database
        DB::table('konfirmhilangkembali')->where('idbarang','=',$idbarang)->delete();

        //kembalikan statusnya menjadi 1
        DB::table('barang')->where('idbarang','=',$idbarang)->update([
            'statusdbr' => 1
        ]);
    }

}
