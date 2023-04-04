<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\AdminAnggaran\AnggaranBagianModel;

class AnggaranBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $judul = 'Anggaran Bagian';
        $tahunanggaran = session('tahunanggaran');
        $datadeputi = DB::table('deputi')->get();
        $databiro = DB::table('biro')->get();
        $databagian = DB::table('bagian')->get();

        if ($request->ajax()) {
            $data = AnggaranBagianModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->indeks.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editanggaran">Edit</a>';
                    return $btn;
                })
                ->addColumn('idbagian',function ($row){
                    $idbagian = $row->idbagian;
                    $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbagian;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('AdminAnggaran.anggaranbagian',[
            "judul"=>$judul,
            "datadeputi" => $datadeputi,
            "databiro" => $databiro,
            "databagian" => $databagian
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $menu = DB::table('anggaranbagian')->where('indeks','=',$id)->get();
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
        DB::table('anggaranbagian')->where('indeks','=',$id)->update([
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'idbagian' => $request->get('idbagian')
            ]);
        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        AnggaranBagianModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
