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
            $data = AdministrasiUserModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('iddeputi', function($row){
                    if ($row->iddeputi != 0){
                        $uraiandeputi = DB::table('deputi')->where('id','=',$row->iddeputi)->value('uraiandeputi');
                    }else{
                        $uraiandeputi = 0;
                    }
                    return $uraiandeputi;
                })
                ->addColumn('idbiro', function($row){
                    if ($row->idbiro != 0){
                        $uraianbiro = DB::table('biro')->where('id','=',$row->idbiro)->value('uraianbiro');
                    }else{
                        $uraianbiro = 0;
                    }
                    return $uraianbiro;
                })
                ->addColumn('idbagian', function($row){
                    if ($row->idbagian != 0){
                        $uraianbagian = DB::table('bagian')->where('id','=',$row->idbagian)->value('uraianbagian');
                    }else{
                        $uraianbagian = 0;
                    }
                    return $uraianbagian;
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
