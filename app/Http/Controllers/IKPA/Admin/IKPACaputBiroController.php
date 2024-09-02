<?php

namespace App\Http\Controllers\IKPA\Admin;


use App\Exports\ExportIkpaCaputBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaCaputBiro;
use App\Models\IKPA\Admin\IKPACaputBiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPACaputBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Capaian Output Biro';
        $databagian = DB::table('biro')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpacaputbiro',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }


    public function getdataikpacaput(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPACaputBiroModel::with('birorelation')
                ->select(['ikpacaputbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPACaputBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }


    public function hitungikpacaputbiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaCaputBiro($tahunanggaran));
        return redirect()->to('monitoringikpacaputbiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpacaputbiro(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaCaputBiro($tahunanggaran),'IKPACaputBiro.xlsx');
    }

    function hitungikpaperro($tahunanggaran){
        $dataro = DB::table('ro')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($dataro as $data){
            $kodesatker = $data->kodesatker;
            $kodekegiatan = $data->kodekegiatan;
            $kodeoutput = $data->kodeoutput;
            $kodesuboutput = $data->kodesuboutput;
            $satuan = $data->satuan;
            $idbiro = $data->idbiro;
            $idro = $data->id;

            //HITUNG NILAI IKPA INDIKATOR
            for($i=1; $i<=12;$i++){
                $realisasibulan = DB::table('realisasiro')
                    ->where('tahunanggaran','=',$tahunanggaran)
                    ->where('idro','=',$idro)
                    ->where('periode','=',DB::raw($i));
                $jumlah = $realisasibulan->count();
                if ($jumlah > 0){
                    $datarealisasibulan = $realisasibulan->get();
                    foreach ($datarealisasibulan as $drb){
                        $jumlah = $drb->jumlah;
                        $target = $drb->target;
                        $keterangan = $drb->keterangan;
                        $targetbulan = $drb->targetbulan;
                        if ($keterangan == "Normalisasi"){
                            $nilaiketepatan = 0.00;
                        }else{
                            $nilaiketepatan = 0.3*100;
                        }
                        if ($targetbulan == 0 and $keterangan != "Normalisasi"){
                            $nilaiketercapaian = 0.7*100.00;
                        }else if ($targetbulan == 0 and $keterangan == "Normalisasi"){
                            $nilaiketercapaian = 0.00;
                        }else{
                            $nilaiketercapaian = 0.7*(($jumlah/$targetbulan)*100);
                            if ($nilaiketercapaian > 70.00){
                                $nilaiketercapaian = 70.00;
                            }
                        }

                        $nilaiikpa = $nilaiketepatan+$nilaiketercapaian;
                    }

                }else{
                    $jumlah = 0.00;
                    $target = 0.00;
                    $targetbulan = 0.00;
                    $nilaiketepatan = 0.00;
                    $nilaiketercapaian = 0.00;
                    $nilaiikpa = 0.00;
                }

                $datainsert = array(
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $i,
                    'kodesatker' => $kodesatker,
                    'idbiro' => $idbiro,
                    'kodekegiatan' => $kodekegiatan,
                    'kodeoutput' => $kodeoutput,
                    'kodesuboutput' => $kodesuboutput,
                    'totaltarget' => $target,
                    'satuan' => $satuan,
                    'realisasikinerja' => $jumlah,
                    'targetvolbulan' => $targetbulan,
                    'nilaiketepatan' => $nilaiketepatan,
                    'nilaiketercapaian' => $nilaiketercapaian,
                    'nilaiikpa' => $nilaiikpa
                );
                DB::table('detailikparo')->updateOrInsert([
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $i,
                    'kodesatker' => $kodesatker,
                    'idbiro' => $idbiro
                    ],$datainsert);
            }

        }
        $this->aksiperhitunganikpacaputbiro($tahunanggaran);

    }

    public function aksiperhitunganikpacaputbiro($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databiro = DB::table('biro')->where('status','=','on')->get();

            foreach ($databiro as $db){
                $idbiro = $db->id;
                for($i=1;$i<=12;$i++){
                    $rerataikpacapaian = DB::table('detailikparo')
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',DB::raw($i))
                        ->where('kodesatker','=',$kodesatker)
                        ->avg('nilaiketercapaian');
                    $rerataketepatan = DB::table('detailikparo')
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',DB::raw($i))
                        ->where('kodesatker','=',$kodesatker)
                        ->avg('nilaiketepatan');
                    $nilaiikpa = $rerataikpacapaian+$rerataketepatan;
                    $datainsert = array(
                        'kodesatker' => $kodesatker,
                        'tahunanggaran' => $tahunanggaran,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'rerataikpacapaian' => $rerataikpacapaian,
                        'rerataikpaketepatan' => $rerataketepatan,
                        'nilaiikpa' => $nilaiikpa
                    );
                    if ($rerataketepatan !== null or $rerataikpacapaian != null){
                        DB::table('ikpacaputbiro')->updateOrInsert([
                            'kodesatker'=>$kodesatker,
                            'tahunanggaran' => $tahunanggaran,
                            'periode' => $i,
                            'idbiro' => $idbiro
                        ],$datainsert);
                    }
                }
            }
        }
    }
}
