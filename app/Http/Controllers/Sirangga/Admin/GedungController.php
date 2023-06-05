<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\GedungModel;
use App\Models\Sirangga\Admin\SubAreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GedungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Gedung';
        $dataarea = AreaModel::all();
        if ($request->ajax()) {
            $data = GedungModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editgedung">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletegedung">Delete</a>';
                    return $btn;
                })
                ->addColumn('idarea',function ($row){
                    $idarea = $row->idarea;
                    $uraianarea = DB::table('area')->where('id','=',$idarea)->value('uraianarea');
                    return $uraianarea;
                })
                ->addColumn('idsubarea',function ($row){
                    $idsubarea = $row->idsubarea;
                    $uraiansubarea = DB::table('subarea')->where('id','=',$idsubarea)->value('uraiansubarea');
                    return $uraiansubarea;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.Admin.gedung',[
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
            'idsubarea' => 'required',
            'kodegedung' => 'required|max:3',
            'uraiangedung' => 'required|max:200'
        ]);

        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $kodegedung =$request->get('kodegedung');
        $uraiangedung = $request->get('uraiangedung');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'kodegedung' => $kodegedung,
            'uraiangedung' => $uraiangedung
        );
        $adadata = DB::table('gedung')->where($where)->count();
        if ($adadata > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('gedung')->insert($where);
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
        $gedung = GedungModel::find($id);
        return response()->json($gedung);
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
            'idsubarea' => 'required',
            'kodegedung' => 'required|max:3',
            'uraiangedung' => 'required|max:200'
        ]);
        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $kodegedung =$request->get('kodegedung');
        $uraiangedung = $request->get('uraiangedung');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'kodegedung' => $kodegedung,
            'uraiangedung' => $uraiangedung
        );
        $adadata = DB::table('gedung')->where($where)->count();
        if ($adadata > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('gedung')->where('id','=',$id)->update($where);
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
        //cek apakah sudah dipakai dilantai
        $lantai = DB::table('lantai')->where('idgedung','=',$id)->count();
        if ($lantai==0){
            GedungModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function dapatkansubarea(Request $request){
        $data['subarea'] = DB::table('subarea')
            ->where('idarea','=',$request->idarea)
            ->get(['id','uraiansubarea']);

        return response()->json($data);
    }
}
