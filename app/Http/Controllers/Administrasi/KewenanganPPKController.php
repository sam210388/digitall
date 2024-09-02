<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\KewenanganPPKModel;
use App\Models\Administrasi\MenuModel;
use App\Models\Administrasi\PPKSatkerModel;
use App\Models\Administrasi\SubMenuModel;
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KewenanganPPKController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Kewenangan PPK';
        $datappk = PPKSatkerModel::all();
        $databiro = BiroModel::where('status','=','on')->get();
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = KewenanganPPKModel::with('ppkrelation')
                ->with('birorelation')
                ->where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';

                    return $btn;
                })
                ->addColumn('ppk',function(KewenanganPPKModel $row){
                    return $row->idppk?$row->ppkrelation->uraianppk:"";
                })
                ->addColumn('biro',function(KewenanganPPKModel $row){
                    return $row->idbiro?$row->birorelation->uraianbiro:"";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.kewenanganppk',[
            "judul"=>$judul,
            "datappk" => $datappk,
            "databiro" => $databiro

        ]);
    }

    public function ambillistppk(Request $request){
        $kodesatker = $request->get('kodesatker');
        $data['ppk'] = DB::table('ppksatker as a')
            ->where('a.kodesatker','=',$kodesatker)
            ->where('status','=','on')
            ->get(['id','uraianppk']);
        return response()->json($data);
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
            'idppk' => 'required',
            'idbiro' => 'required',
            'kodesatker' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        KewenanganPPKModel::create(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'idppk' => $request->get('idppk'),
                'idbiro' => $request->get('idbiro')
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
        $menu = KewenanganPPKModel::find($id);
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
        $validated = $request->validate([
            'idppk' => 'required',
            'idbiro' => 'required',
            'kodesatker' => 'required'
        ]);
        $tahunanggaran = session('tahunanggaran');
        KewenanganPPKModel::where('id','=',$id)->update(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $request->get('kodesatker'),
                'idppk' => $request->get('idppk'),
                'idbiro' => $request->get('idbiro')
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
        KewenanganPPKModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
