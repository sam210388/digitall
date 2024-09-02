<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\LantaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LantaiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data lantai';
        $dataarea = AreaModel::all();
        if ($request->ajax()) {
            $data = LantaiModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editlantai">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletelantai">Delete</a>';
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
                ->addColumn('idgedung',function ($row){
                    $idgedung= $row->idgedung;
                    $uraiangedung = DB::table('gedung')->where('id','=',$idgedung)->value('uraiangedung');
                    return $uraiangedung;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.Admin.lantai',[
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
            'idgedung' => 'required',
            'kodelantai' => 'required|max:3',
            'uraianlantai' => 'required|max:200'
        ]);

        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $kodelantai =$request->get('kodelantai');
        $uraianlantai = $request->get('uraianlantai');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'idgedung' => $idgedung,
            'kodelantai' => $kodelantai,
            'uraianlantai' => $uraianlantai
        );
        $adadata = DB::table('lantai')->where($where)->count();
        if ($adadata > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('lantai')->insert($where);
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
        $lantai = LantaiModel::find($id);
        return response()->json($lantai);
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
            'idgedung' => 'required',
            'kodelantai' => 'required|max:3',
            'uraianlantai' => 'required|max:200'
        ]);
        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $kodelantai =$request->get('kodelantai');
        $uraianlantai = $request->get('uraianlantai');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'idgedung' => $idgedung,
            'kodelantai' => $kodelantai,
            'uraianlantai' => $uraianlantai
        );
        $adadata = DB::table('lantai')->where($where)->count();
        if ($adadata > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('lantai')->where('id','=',$id)->update($where);
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
        $ruangan = DB::table('ruangan')->where('idlantai','=',$id)->count();
        if ($ruangan==0){
            LantaiModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function dapatkangedung(Request $request){
        $data['gedung'] = DB::table('gedung')
            ->where('idsubarea','=',$request->idsubarea)
            ->get(['id','uraiangedung']);
        return response()->json($data);
    }
}
