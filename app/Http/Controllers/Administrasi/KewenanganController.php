<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrasi\KewenanganModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class KewenanganController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $judul = 'Data Kewenangan';
        if ($request->ajax()) {

            $data = DB::table('role')
                ->where('id','!=',1)
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editKewenangan">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteKewenangan">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.kewenangan',[
            "judul"=>$judul
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
            'kewenangan' => 'required|max:100',
            'deskripsi' => 'required|max:200',
        ]);
        KewenanganModel::create(
            [
                'kewenangan' => $request->get('kewenangan'),
                'deskripsi' => $request->get('deskripsi')
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
        $kewenangan = KewenanganModel::find($id);
        return response()->json($kewenangan);
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
            'kewenangan' => 'required|max:100',
            'deskripsi' => 'required|max:200',
        ]);
        KewenanganModel::where('id',$id)->update(
            [
                'kewenangan' => $request->get('kewenangan'),
                'deskripsi' => $request->get('deskripsi')
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
        //cek apakah ada kewenangan sudah dipakai
        $adadata = DB::table('role_users')->where('idrole','=',$id)->count();
        if ($adadata == 0){
            KewenanganModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status' => 'gagal']);
        }

    }
}
