<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\AreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Area';
        if ($request->ajax()) {
            $data = DB::table('area')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editarea">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletearea">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.admin.area',[
            "judul"=>$judul
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodearea' => 'required|max:2',
            'uraianarea' => 'required|max:200',
        ]);

        $kodearea = $request->get('kodearea');
        $uraianarea = $request->get('uraianarea');
        $where = array(
            'kodearea' => $kodearea,
            'uraianarea' => $uraianarea
        );
        $ada = DB::table('area')->where($where)->count();
        if ($ada == 0){
            DB::table('area')->insert($where);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
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
        $area = AreaModel::find($id);
        return response()->json($area);
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
            'kodearea' => 'required',
            'uraianarea' => 'required|max:200',
        ]);

        $kodearea = $request->get('kodearea');
        $uraianarea = $request->get('uraianarea');
        $where = array(
            'kodearea' => $kodearea,
            'uraianarea' => $uraianarea
        );
        $ada = DB::table('area')->where($where)->count();
        if ($ada >1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('area')->where('id','=',$id)->update($where);
            return response()->json(['status'=>'berhasil']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cek apakah sudah pernah dipakai di subarea
        $subarea = DB::table('subarea')->where([
            'idarea' => $id
        ])->count();
        if ($subarea > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            AreaModel::find($id)->delete();
            return response()->json(['status'=>'gagal']);
        }
    }
}
