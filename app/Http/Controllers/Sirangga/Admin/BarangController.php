<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dapatkandataaset(Request $request){
        $data['barang'] = DB::table('barang')
            ->where('kd_brg','=',$request->kodebarang)
            ->get(['no_aset','no_aset']);
        return response()->json($data);
    }

    public function barang(){
        $judul = "Data Barang";
        return view('Sirangga.Admin.barang',[
            "judul"=>$judul,
        ]);
    }

    public function getdatabarang(){
        $data = BarangModel::with('kodebarangrelation')
            ->select('barang.*');

        return Datatables::eloquent($data)
            ->addColumn('ur_sskel', function (BarangModel $barang) {
                return $barang->kodebarangrelation->ur_sskel;
            })
            ->editColumn('statusdbr', function ($row) {
                if ($row->statusdbr == 2){
                    $kd_lokasi = $row->kd_lokasi;
                    $kd_brg = $row->kd_brg;
                    $no_aset = $row->no_aset;
                    $iddbr = DB::table('detildbr')
                        ->where('kd_lokasi','=',$kd_lokasi)
                        ->where('kd_brg','=',$kd_brg)
                        ->where('no_aset','=',$no_aset)
                        ->value('iddbr');
                    return "IDDBR ".$iddbr;
                }else{
                    return "Belum DBR";
                }
            })
            ->toJson();

    }



}
