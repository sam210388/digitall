<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\PenghapusanBarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonitoringPenghapusanBarangController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function penghapusanbarang(){
        $judul = "Data Penghapusan Barang";
        return view('Sirangga.Admin.penghapusanbarang',[
            "judul"=>$judul,
        ]);
    }

    public function getdatapenghapusanbarang(){
        $data = PenghapusanBarangModel::with('kodebarangrelation')
            ->select('penghapusanbarang.*');

        return Datatables::eloquent($data)
            ->addColumn('ur_sskel', function (PenghapusanBarangModel $barang) {
                return $barang->kodebarangrelation->ur_sskel;
            })
            ->toJson();

    }

    public function rekappenghapusanbarang(){
        $datapenghapusanbarang = DB::table('mastersakti')->where([
            'kdtrx' => 401
        ])->get();
    }



}
