<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\SubAreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SubAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Sub Area';
        $dataarea = AreaModel::all();
        if ($request->ajax()) {

            $data = SubAreaModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editsubarea">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletesubarea">Delete</a>';
                    return $btn;
                })
                ->addColumn('idarea',function ($row){
                    $idarea = $row->idarea;
                    $uraianarea = DB::table('area')->where('id','=',$idarea)->value('uraianarea');
                    return $uraianarea;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.Admin.subarea',[
            "judul"=>$judul,
            "dataarea" => $dataarea
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'idarea' => 'required',
            'kodesubarea' => 'required|max:4',
            'uraiansubarea' => 'required|max:200'
        ]);

        $idarea = $request->get('idarea');
        $kodesubarea =$request->get('kodesubarea');
        $uraiansubarea = $request->get('uraiansubarea');

        $where = array(
            'idarea' => $idarea,
            'kodesubarea' => $kodesubarea,
            'uraiansubarea' => $uraiansubarea
        );
        $adadata = DB::table('subarea')->where($where)->count();
        if ($adadata > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('subarea')->insert($where);
            return response()->json(['status'=>'berhasil']);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subarea = SubAreaModel::find($id);
        return response()->json($subarea);
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
            'idarea' => 'required',
            'kodesubarea' => 'required|max:4',
            'uraiansubarea' => 'required|max:200'
        ]);
        $idarea = $request->get('idarea');
        $kodesubarea =$request->get('kodesubarea');
        $uraiansubarea = $request->get('uraiansubarea');

        $where = array(
            'idarea' => $idarea,
            'kodesubarea' => $kodesubarea,
            'uraiansubarea' => $uraiansubarea
        );
        $adadata = DB::table('subarea')->where($where)->count();
        if ($adadata > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('subarea')->where('id','=',$id)->update($where);
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
        //cek apakah sudah dipakai digedung
        $subarea = DB::table('gedung')->where('idsubarea','=',$id)->count();
        if ($subarea==0){
            SubAreaModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
