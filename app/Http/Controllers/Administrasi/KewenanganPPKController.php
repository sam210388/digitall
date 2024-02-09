<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\KewenanganPPKModel;
use App\Models\Administrasi\MenuModel;
use App\Models\Administrasi\SubMenuModel;
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
        $datamenu = MenuModel::all();
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = KewenanganPPKModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';

                    return $btn;
                })
                ->addColumn('ppk',function(KewenanganPPKModel $row){
                    return $row->idppk?$row->ppkrelation->uraianppk:"";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.kewenanganppk',[
            "judul"=>$judul,
            "datamenu" => $datamenu
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
        $validated = $request->validate([
            'idppk' => 'required',
            'idbiro' => 'required',
        ]);
        $tahunanggaran = session('tahunanggaran');
        $kodesatker = DB::table('ppksatker')->where('id','=',$request->get('idppk'))->value('kodesatker');
        KewenanganPPKModel::create(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $kodesatker,
                'idppk' => $request->get('idppk'),
                'idbiro' => $request->get('idbiro'),
                'status' => $active
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
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $validated = $request->validate([
            'idppk' => 'required',
            'idbiro' => 'required',
        ]);
        $tahunanggaran = session('tahunanggaran');
        $kodesatker = DB::table('ppksatker')->where('id','=',$request->get('idppk'))->value('kodesatker');
        KewenanganPPKModel::where('id',$id)->update(
            [
                'tahunanggaran' =>$tahunanggaran,
                'kodesatker' => $kodesatker,
                'idppk' => $request->get('idppk'),
                'idbiro' => $request->get('idbiro'),
                'status' => $active
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
