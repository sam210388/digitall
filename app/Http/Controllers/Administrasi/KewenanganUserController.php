<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\KewenanganModel;
use App\Models\Administrasi\KewenanganUserModel;
use App\Models\Administrasi\MenuModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KewenanganUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','aksesmenu']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Kewenangan User';
        $datauser = User::all();
        $datakewenangan = KewenanganModel::all();

        if ($request->ajax()) {

            $data = KewenanganUserModel::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editkewenanganuser">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletekewenanganuser">Delete</a>';

                    return $btn;
                })
                ->addColumn('iduser',function ($row){
                    $iduser = $row->iduser;
                    $namauser = DB::table('users')->where('id','=',$iduser)->value('name');
                    return $namauser;
                })
                ->addColumn('idrole',function ($row){
                    $idrole = $row->idrole;
                    $kewenangan = DB::table('role')->where('id','=',$idrole)->value('kewenangan');
                    return $kewenangan;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.kewenanganuser',[
            "judul"=>$judul,
            "datauser" => $datauser ,
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
        KewenanganUserModel::updateOrCreate(
            [
                'iduser' => $request->get('iduser'),
                'idrole' => $request->get('idrole')
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
        $menu = KewenanganUserModel::find($id);
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
    public function destroy($id)
    {
        KewenanganUserModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
