<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Models\Sirangga\Admin\AreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Switch_;
use Yajra\DataTables\DataTables;

class TokenApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Api';
        if ($request->ajax()) {
            $data = DB::table('tokenapi')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="resettoken" class="btn btn-success btn-sm resettoken">Reset Token</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="edittoken" class="btn btn-info btn-sm edittoken">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="deletetoken" class="btn btn-danger btn-sm deletetoken">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.tokenapi',[
            "judul"=>$judul
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahunanggaran' => 'required|max:4',
            'kodemodul' => 'required|max:200',
            'token' => 'required'
        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodemodul = $request->get('kodemodul');
        $token = $request->get('token');
        $where = array(
            'tahunanggaran' => $tahunanggaran,
            'modul' => $kodemodul
        );
        $ada = DB::table('tokenapi')->where($where)->count();
        if ($ada == 0){
            DB::table('tokenapi')->insert([
                'tahunanggaran' => $tahunanggaran,
                'modul' => $kodemodul,
                'token' => $token
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function edit($id)
    {
        $token = DB::table('tokenapi')->get();
        return response()->json($token);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tahunanggaran' => 'required|max:4',
            'kodemodul' => 'required|max:200',
            'token' => 'required'
        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodemodul = $request->get('kodemodul');
        $token = $request->get('token');
        $where = array(
            'tahunanggaran' => $tahunanggaran,
            'modul' => $kodemodul
        );
        $ada = DB::table('tokenapi')->where($where)->count();
        if ($ada > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('tokenapi')->where('id','=',$id)->update([
                'tahunanggaran' => $tahunanggaran,
                'modul' => $kodemodul,
                'token' => $token
            ]);
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function resettoken($id){
        $datatoken = DB::table('tokenapi')->where('id','=',$id)->get();
        foreach ($datatoken as $dt){
            $modul = $dt->modul;
            $tahunanggaran = $dt->tahunanggaran;

            //dapatkan tipedata
            Switch ($modul){
                case "ADM":
                    $tipedata = "refAset";
                    break;
                case "ANG":
                    $tipedata = "dataAng";
                    break;
                case "AST":
                    $tipedata = "asetTrx";
                    break;
                case "PEM":
                    $tipedata = "realisasi";
                    break;
                case "BEN":
                    $tipedata = "kasTunai";
                    break;
                case "GLP":
                    $tipedata = "bukuBesar";
                    break;
                case "KOM":
                    $tipedata = "capaianRO";
                    break;
                case "PER":
                    $tipedata = "persediaTrx";
                    break;
            }

            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $modul, $tipedata);
        }
        return response()->json(['status'=>'berhasil']);
    }

}
