<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\KroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class KroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'List KRO';
        $tahunanggaran = session('tahunanggaran');

        $datatahunanggaran = DB::table('tahunanggaran')->get();
        $datakegiatan = DB::table('kegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();


        if ($request->ajax()) {
            $data = KroModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editkro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletekro">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.kro',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran,
            "datakegiatan" => $datakegiatan
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
            'tahunanggaran' => 'required',
            'temuan' => 'required',
            'kondisi' => 'required',
            'kriteria' => 'required',
            'sebab' => 'required',
            'akibat' => 'required',
            'nilai' => 'required',
            'bukti' => 'required',

        ]);

        KroModel::create(
            [
                'tahunanggaran' => $request->get('tahunanggaran'),
                'temuan' => $request->get('temuan'),
                'kondisi' => $request->get('kondisi'),
                'kriteria' => $request->get('kriteria'),
                'sebab' => $request->get('sebab'),
                'akibat' => $request->get('akibat'),
                'nilai' => $request->get('nilai'),
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
        $status = DB::table('temuan')->where('id','=',$id)->value('status');
        if ($status == 1){
            $menu = KroModel::find($id);
            return response()->json($menu);
        }else{
            return response()->json(['status'=>'gagal']);
        }
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
            'tahunanggaran' => 'required',
            'temuan' => 'required',
            'kondisi' => 'required',
            'kriteria' => 'required',
            'sebab' => 'required',
            'akibat' => 'required',
            'nilai' => 'required',

        ]);

        KroModel::where('id',$id)->update(
            [
                'tahunanggaran' => $request->get('tahunanggaran'),
                'temuan' => $request->get('temuan'),
                'kondisi' => $request->get('kondisi'),
                'kriteria' => $request->get('kriteria'),
                'sebab' => $request->get('sebab'),
                'akibat' => $request->get('akibat'),
                'nilai' => $request->get('nilai')
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
        $status = DB::table('rekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            KroModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
