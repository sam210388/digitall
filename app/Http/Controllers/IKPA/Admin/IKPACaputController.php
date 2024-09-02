<?php

namespace App\Http\Controllers\IKPA\Admin;


use App\Exports\ExportIkpaCaputBagian;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaCaputBagian;
use App\Models\IKPA\Admin\IKPADetailCaputModel;
use App\Models\IKPA\Admin\IKPACaputBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPACaputController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Capaian Output';
        $databagian = DB::table('bagian')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpacaputbagian',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }


    public function getdataikpacaput(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPACaputBagianModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpacaputbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPACaputBagianModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPACaputBagianModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }


    public function hitungikpacaputbagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaCaputBagian($tahunanggaran));
        return redirect()->to('monitoringikpacaputbagian')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpacaputbagian(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaCaputBagian($tahunanggaran),'IKPACaputBagian.xlsx');
    }

    function hitungikpaperindikatorro($tahunanggaran){
        $dataindikatorro = DB::table('indikatorro')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($dataindikatorro as $data){
            $kodesatker = $data->kodesatker;
            $idbagian = $data->idbagian;
            $idbiro = $data->idbiro;
            $idindikatorro = $data->id;
            $kodekegiatan = $data->kodekegiatan;
            $kodeoutput = $data->kodeoutput;
            $kodesuboutput = $data->kodesuboutput;
            $kodekomponen = $data->kodekomponen;
            $satuan = $data->satuan;

            //HITUNG NILAI IKPA INDIKATOR
            for($i=1; $i<=12;$i++){
                $realisasibulan = DB::table('realisasiindikatorro')
                    ->where('tahunanggaran','=',$tahunanggaran)
                    ->where('idindikatorro','=',$idindikatorro)
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
                    'idbagian' => $idbagian,
                    'idindikatorro' => $idindikatorro,
                    'kodekegiatan' => $kodekegiatan,
                    'kodeoutput' => $kodeoutput,
                    'kodesuboutput' => $kodesuboutput,
                    'kodekomponen' => $kodekomponen,
                    'totaltarget' => $target,
                    'satuan' => $satuan,
                    'realisasikinerja' => $jumlah,
                    'targetvolbulan' => $targetbulan,
                    'nilaiketepatan' => $nilaiketepatan,
                    'nilaiketercapaian' => $nilaiketercapaian,
                    'nilaiikpa' => $nilaiikpa
                );
                DB::table('detailikpaindikatorro')->updateOrInsert([
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $i,
                    'kodesatker' => $kodesatker,
                    'idindikatorro' => $idindikatorro

                    ],$datainsert);
            }

        }
        $this->aksiperhitunganikpacaputbagian($tahunanggaran);

    }

    public function aksiperhitunganikpacaputbagian($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databagian = DB::table('bagian')->where('status','=','on')->get();

            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;
                for($i=1;$i<=12;$i++){
                    $rerataikpacapaian = DB::table('detailikpaindikatorro')
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',DB::raw($i))
                        ->where('kodesatker','=',$kodesatker)
                        ->avg('nilaiketercapaian');
                    $rerataketepatan = DB::table('detailikpaindikatorro')
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',DB::raw($i))
                        ->where('kodesatker','=',$kodesatker)
                        ->avg('nilaiketepatan');
                    $nilaiikpa = $rerataikpacapaian+$rerataketepatan;
                    $datainsert = array(
                        'kodesatker' => $kodesatker,
                        'tahunanggaran' => $tahunanggaran,
                        'periode' => $i,
                        'idbagian' => $idbagian,
                        'idbiro' => $idbiro,
                        'rerataikpacapaian' => $rerataikpacapaian,
                        'rerataikpaketepatan' => $rerataketepatan,
                        'nilaiikpa' => $nilaiikpa
                    );
                    if ($rerataketepatan !== null or $rerataikpacapaian != null){
                        DB::table('ikpacaputbagian')->updateOrInsert([
                            'kodesatker'=>$kodesatker,
                            'tahunanggaran' => $tahunanggaran,
                            'periode' => $i,
                            'idbagian' => $idbagian,
                            'idbiro' => $idbiro
                        ],$datainsert);
                    }
                }
            }
        }
    }
}
