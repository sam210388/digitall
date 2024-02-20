<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\AdminAnggaran\AnggaranBagianModel;

class AnggaranBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getanggaransetjenkosong(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = AnggaranBagianModel::where('tahunanggaran','=',$tahunanggaran)
                ->where('kdsatker','=','001012')
                ->whereNull('idbagian')
                ->orWhere('idbagian','=',0)
                ->get();
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
    }

    public function getanggarandewankosong(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = AnggaranBagianModel::where('tahunanggaran','=',$tahunanggaran)
                ->where('kdsatker','=','001030')
                ->whereNull('idbagian')
                ->orWhere('idbagian','=',0)
                ->get();
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
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Anggaran Bagian';
        $datadeputi = DB::table('deputi')->get();
        $databiro = DB::table('biro')->get();
        $databagian = DB::table('bagian')->get();
        $anggaransetjenkosong = DB::table('anggaranbagian')
            ->where('kdsatker','=','001012')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->whereNull('idbagian')
            ->orWhere('idbagian','=',0)
            ->count();
        $anggarandewankosong = DB::table('anggaranbagian')
            ->where('kdsatker','=','001030')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->whereNull('idbagian')
            ->orWhere('idbagian','=',0)
            ->count();
        return view('AdminAnggaran.anggaranbagian',[
            "judul"=>$judul,
            "datadeputi" => $datadeputi,
            "databiro" => $databiro,
            "databagian" => $databagian,
            "anggaransetjenkosong" => $anggaransetjenkosong,
            "anggarandewankosong" => $anggarandewankosong
        ]);
    }

    public function getdataanggaranbagian(Request $request, $status){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = AnggaranBagianModel::where('tahunanggaran','=',$tahunanggaran);
            if ($status == 2) {
                $data->where('kdsatker','=','001012')
                    ->whereNull('idbagian');
            }else if($status == 3){
                $data->where('kdsatker','=','001030')
                    ->whereNull('idbagian');
            }
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
