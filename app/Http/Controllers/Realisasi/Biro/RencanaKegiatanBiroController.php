<?php

namespace App\Http\Controllers\Realisasi\Biro;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RencanaKegiatanBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Rencana Kegiatan';
        $idbiro = Auth::user()->idbiro;
        $datapengenal = DB::table('laporanrealisasianggaranbac')->where('idbiro','=',$idbiro)->get();
        $databagian = DB::table('bagian')->where('idbiro','=',$idbiro)->get();
        return view('Realisasi.Biro.rencanakegiatanbiro',[
            "judul"=>$judul,
            "datapengenal" => $datapengenal,
            "databagian" => $databagian
        ]);
    }

    public function getdatarencanakegiatanbagian($idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $idbiro = Auth::user()->idbiro;
        $model = RencanaKegiatanModel::with('bagianpengajuanrelation')
            ->select('rencanakegiatan.*')
            ->where('idbiro','=',$idbiro)
            ->where('tahunanggaran','=',$tahunanggaran);
        if ($idbagian != null){
            $model->where('idbagian','=',$idbagian);
        }
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('bagian', function (RencanaKegiatanModel $id) {
                return $id->idbagian ? $id->bagianpengajuanrelation->uraianbagian:"";
            })
            ->addColumn('action', function($row){
                if ($row->statusubah == "Open" && $row->statusrencana == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Edit</a>';
                    //$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm ajukankeppk">Kirim PPK</a>';
                }else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }

    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
    }


    public function update(Request $request, $id)
    {
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'uraiankegiatanrinci' => 'required',
            'paguanggaran' => 'required',
            'totalrencana' => 'required',
            'januari' => 'required',
            'februari' => 'required',
            'maret' => 'required',
            'april' => 'required',
            'mei' => 'required',
            'juni' => 'required',
            'juli'=> 'required',
            'agustus' => 'required',
            'september' => 'required',
            'oktober' => 'required',
            'november' => 'required',
            'desember' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        $uraiankegiatanrinci = $request->get('uraiankegiatanrinci');
        $paguanggaran = $request->get('paguanggaran');
        $totalrencana = $request->get('totalrencana');
        $januari = $request->get('januari');
        $januari = $this->formatulang($januari);
        $februari = $request->get('februari');
        $februari = $this->formatulang($februari);
        $maret = $request->get('maret');
        $maret = $this->formatulang($maret);
        $april = $request->get('april');
        $april = $this->formatulang($april);
        $mei = $request->get('mei');
        $mei = $this->formatulang($mei);
        $juni = $request->get('juni');
        $juni = $this->formatulang($juni);
        $juli = $request->get('juli');
        $juli = $this->formatulang($juli);
        $agustus = $request->get('agutus');
        $agustus = $this->formatulang($agustus);
        $september = $request->get('september');
        $september = $this->formatulang($september);
        $oktober = $request->get('oktober');
        $oktober = $this->formatulang($oktober);
        $november = $request->get('november');
        $november = $this->formatulang($november);
        $desember = $request->get('desember');
        $desember = $this->formatulang($desember);
        $idbagian = Auth::user()->idbagian;
        $idbiro = Auth::user()->idbiro;

        RencanaKegiatanModel::where('id','=',$id)->update(
            [
                'uraiankegiatanbagian' => $uraiankegiatanrinci,
                'paguanggaran' => $paguanggaran,
                'totalrencana' => $totalrencana,
                'statusubah' => "Open",
                'created_at' => now(),
                'created_by' => Auth::user()->id,
                'updated_at' => now(),
                'updated_by' => Auth::user()->id,
                'statusrencana' => "Draft",
                'pok1' => $januari,
                'pok2' => $februari,
                'pok3' => $maret,
                'pok4' => $april,
                'pok5' => $mei,
                'pok6' => $juni,
                'pok7' => $juli,
                'pok8' => $agustus,
                'pok9' => $september,
                'pok10' => $oktober,
                'pok11' => $november,
                'pok12' => $desember
            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = RencanaKegiatanModel::find($id);
        return response()->json($menu);
    }
}
