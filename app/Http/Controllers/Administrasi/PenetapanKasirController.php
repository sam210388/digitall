<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\PenetapanKasirModel;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenetapanKasirController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data User Kasir dan Kewenangannya';
        $datauser = User::all();
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = PenetapanKasirModel::with('userrelation')
                ->where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';
                    return $btn;
                })
                ->addColumn('user',function(PenetapanKasirModel $row){
                    return $row->iduser?$row->userrelation->name:"";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.penetapankasir',[
            "judul"=>$judul,
            "datauser" => $datauser
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodesatker' => 'required',
            'iduser' => 'required',
        ]);
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $tahunanggaran = session('tahunanggaran');
        PenetapanKasirModel::create(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'iduser' => $request->get('iduser'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = PenetapanKasirModel::find($id);
        return response()->json($menu);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kodesatker' => 'required',
            'iduser' => 'required',
        ]);
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $tahunanggaran = session('tahunanggaran');
        PenetapanKasirModel::where('id','=',$id)->update(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'iduser' => $request->get('iduser'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        PenetapanKasirModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
