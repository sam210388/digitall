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
                    return "Sudah DBR";
                }else{
                    return "Belum DBR";
                }
            })
            ->toJson();

    }



}
