<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\PenetapanPPKModel;
use App\Models\Administrasi\PPKSatkerModel;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenetapanPPKController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data User PPK dan Kewenangannya';
        $datappk = PPKSatkerModel::all();
        $datauser = User::all();
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = PenetapanPPKModel::with('ppkrelation')
                ->with('userrelation')
                ->where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';
                    return $btn;
                })
                ->addColumn('ppk',function(PenetapanPPKModel $row){
                    return $row->idppk?$row->ppkrelation->uraianppk:"";
                })
                ->addColumn('user',function(PenetapanPPKModel $row){
                    return $row->idppk?$row->userrelation->name:"";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.penetapanppk',[
            "judul"=>$judul,
            "datappk" => $datappk,
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
            'idppk' => 'required',
            'iduser' => 'required',
        ]);
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $tahunanggaran = session('tahunanggaran');
        PenetapanPPKModel::create(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'idppk' => $request->get('idppk'),
                'iduser' => $request->get('iduser'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = PenetapanPPKModel::find($id);
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
            'idppk' => 'required',
            'iduser' => 'required',
        ]);
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $tahunanggaran = session('tahunanggaran');
        PenetapanPPKModel::where('id','=',$id)->update(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'idppk' => $request->get('idppk'),
                'iduser' => $request->get('iduser'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PenetapanPPKModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
