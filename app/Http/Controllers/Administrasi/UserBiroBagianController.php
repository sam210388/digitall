<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\AdministrasiUserModel;
use App\Models\ReferensiUnit\DeputiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UserBiroBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    public function index(Request $request){
        $judul = 'Update Unit Kerja User';
        $deputi = DeputiModel::all();
        if ($request->ajax()) {
            $data = AdministrasiUserModel::with('bagianrelation')
                    ->with('deputirelation')
                    ->with('birorelation')
                    ->where('id','!=',1)
                    ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('iddeputi', function (AdministrasiUserModel $id) {
                    return $id->iddeputi ? $id->deputirelation->uraiandeputi:"";
                })
                ->addColumn('idbiro', function ($id) {
                    $uraianbiro = DB::table('biro')->where('id','=',$id->idbiro)->value('uraianbiro');
                    if ($uraianbiro){
                        $idbiro = $uraianbiro;
                    }else{
                        $idbiro = $id->idbiro;
                    }
                    return $idbiro;
                })
                ->addColumn('idbagian', function ($id) {
                    $uraianbagian = DB::table('bagian')->where('id','=',$id->idbagian)->value('uraianbagian');
                    if ($uraianbagian){
                        $idbagian = $uraianbagian;
                    }else{
                        $idbagian = $id->idbagian;
                    }
                    return $idbagian;
                })


                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edituser">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.userbirobagian',[
            "judul"=>$judul,
            "datadeputi"=> $deputi
        ]);
    }

    public function update(Request $request, $id){
        $idbiro = $request->get('idbiro');
        $idbagian = $request->get('idbagian');
        $iddeputi = $request->get('iddeputi');

        $validated = $request->validate([
            'idbiro' => 'required',
            'idbagian' => 'required',
            'iddeputi' => 'required',
        ]);
        AdministrasiUserModel::where('id','=',$id)->update([
            'idbiro' => $idbiro,
            'idbagian' => $idbagian,
            'iddeputi' => $iddeputi,
        ]);
        return response()->json(['status'=>'berhasil']);

    }

    public function edit($id)
    {
        $menu = AdministrasiUserModel::find($id);
        return response()->json($menu);
    }
}
