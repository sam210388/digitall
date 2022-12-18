<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\MenuModel;
use App\Models\Administrasi\KewenanganModel;
use App\Models\Administrasi\KewenanganMenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class KewenanganMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Menu Kewenangan';
        $datamenu = MenuModel::all();
        $datakewenangan = KewenanganModel::all();
        if ($request->ajax()) {

            $data = KewenanganMenuModel::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editkewenanganmenu">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletekewenanganmenu">Delete</a>';

                    return $btn;
                })
                ->addColumn('idmenu',function ($row){
                    $idmenu = $row->idmenu;
                    $uraianmenu = DB::table('menu')->where('id','=',$idmenu)->value('uraianmenu');
                    return $uraianmenu;
                })
                ->addColumn('idkewenangan',function ($row){
                    $idkewenangan = $row->idkewenangan;
                    $kewenangan = DB::table('role')->where('id','=',$idkewenangan)->value('kewenangan');
                    return $kewenangan;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.kewenanganmenu',[
            "judul"=>$judul,
            "datamenu" => $datamenu,
            "datakewenangan" => $datakewenangan
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
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        KewenanganMenuModel::updateOrCreate(
            ['id' => $request->get('id')],
            [
                'idmenu' => $request->get('idmenu'),
                'idkewenangan' => $request->get('idkewenangan')
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
        $menu = KewenanganMenuModel::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        KewenanganMenuModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
