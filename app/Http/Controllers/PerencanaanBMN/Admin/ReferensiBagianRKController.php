<?php

namespace App\Http\Controllers\PerencanaanBMN\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReferensiBagianRKController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dapatkandatabiro(Request $request){
        $data['biro'] = DB::table('biro')
            ->where('iddeputi','=',$request->iddeputi)
            ->where('status','=','on')
            ->get(['id','uraianbiro']);

        return response()->json($data);
    }

    public function dapatkandatabagian(Request $request){
        $data['bagian'] = DB::table('bagian')
            ->where('idbiro','=',$request->idbiro)
            ->where('status','=','on')
            ->get(['id','uraianbagian']);
        return response()->json($data);
    }

    public function index(Request $request)
    {
        $judul = 'List Bagian';
        $datadeputi = DeputiModel::all();
        $databiro = BiroModel::all();
        if ($request->ajax()) {
            $data = BagianModel::all();
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editbagian">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletebagian">Delete</a>';
                    return $btn;
                })
                ->addColumn('iddeputi',function ($row){
                    $iddeputi = $row->iddeputi;
                    $uraiandeputi = DB::table('deputi')->where('id','=',$iddeputi)->value('uraiandeputi');
                    return $uraiandeputi;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ReferensiUnit.bagian',[
            "judul"=>$judul,
            "datadeputi" => $datadeputi,
            "databiro" => $databiro
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
            $status = "off";
        }else{
            $status = "on";
        }
        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'idbagian' => 'required',
            'uraianbagian' => 'required',

        ]);

        BagianModel::create(
            [
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'id' => $request->get('idbagian'),
                'uraianbagian' => $request->get('uraianbagian'),
                'status' => $status
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = BagianModel::find($id);
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
            $status = "off";
        }else{
            $status = "on";
        }
        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'idbagian' => 'required',
            'uraianbagian' => 'required',

        ]);

        BagianModel::where('id',$id)->update(
            [
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'id' => $request->get('idbagian'),
                'uraianbagian' => $request->get('uraianbagian'),
                'status' => $status
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
        $dipakai = DB::table('temuan')->where('idbagian','=',$id)->count();
        if ($dipakai == 0){
            BagianModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
