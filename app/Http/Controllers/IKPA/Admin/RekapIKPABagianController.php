<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Exports\ExportRekapIKPABagian;
use App\Exports\ExportRekapIKPABiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungRekapIKPABagian;
use App\Jobs\HitungRekapIKPABiro;
use App\Models\IKPA\Admin\RekapIKPABagianlModel;
use App\Models\IKPA\Admin\RekapIKPABiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RekapIKPABagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Rekap IKPA Bagian';
        $databagian = DB::table('bagian')
            ->where('status','=','on')
            ->get();
        return view('IKPA.Admin.rekapikpabagian',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function indexbiro(){
        $judul = 'Rekap IKPA Biro';
        $databiro = DB::table('biro')
            ->where('status','=','on')
            ->get();
        return view('IKPA.Admin.rekapikpabiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }

    public function hitungrekapikpabagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungRekapIKPABagian($tahunanggaran));
        return redirect()->to('rekapikpabagian')->with(['status' => 'Rekapitulasi IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    public function hitungrekapikpabiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungRekapIKPABiro($tahunanggaran));
        return redirect()->to('rekapikpabiro')->with(['status' => 'Rekapitulasi IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportrekapikpabagian(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRekapIKPABagian($tahunanggaran),'RekapIKPABagian.xlsx');
    }

    function exportrekapikpabiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRekapIKPABiro($tahunanggaran),'RekapIKPABiro.xlsx');
    }

    public function getdatarekapikpabagian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =RekapIKPABagianlModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikparekapbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (RekapIKPABagianlModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (RekapIKPABagianlModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function getdatarekapikpabiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =RekapIKPABiroModel::with('birorelation')
                ->select(['ikparekapbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (RekapIKPABiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function aksirekapikpabagian($tahunanggaran){
        $SETJEN_REVISI = 0.1;
        $SETJEN_DEVIASI = 0.15;
        $SETJEN_PENYERAPAN = 0.2;
        $SETJEN_KONTRAKTUAL = 0.1;
        $SETJEN_PENYELESAIAN_TAGIHAN = 0.1;
        $SETJEN_CAPUT = 0.25;
        $SETJEN_KKP = 0.1;

        $DEWAN_REVISI = 0.1;
        $DEWAN_DEVIASI = 0.05;
        $DEWAN_PENYERAPAN = 0.05;
        $DEWAN_KONTRAKTUAL = 0.25;
        $DEWAN_PENYELESAIAN_TAGIHAN = 0.25;
        $DEWAN_CAPUT = 0.1;
        $DEWAN_KKP = 0.2;


        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            if ($kodesatker == "001012"){
                $BOBOT_REVISI = $SETJEN_REVISI;
                $BOBOT_DEVIASI = $SETJEN_DEVIASI;
                $BOBOT_PENYERAPAN = $SETJEN_PENYERAPAN;
                $BOBOT_KONTRAKTUAL = $SETJEN_KONTRAKTUAL;
                $BOBOT_PENYELESAIAN_TAGIHAN = $SETJEN_PENYELESAIAN_TAGIHAN;
                $BOBOT_CAPUT = $SETJEN_CAPUT;
                $BOBOT_KKP = $SETJEN_KKP;
            }else{
                $BOBOT_REVISI = $DEWAN_REVISI;
                $BOBOT_DEVIASI = $DEWAN_DEVIASI;
                $BOBOT_PENYERAPAN = $DEWAN_PENYERAPAN;
                $BOBOT_KONTRAKTUAL = $DEWAN_KONTRAKTUAL;
                $BOBOT_PENYELESAIAN_TAGIHAN = $DEWAN_PENYELESAIAN_TAGIHAN;
                $BOBOT_CAPUT = $DEWAN_CAPUT;
                $BOBOT_KKP = $DEWAN_KKP;
            }
            //ambil data bagian
            $databagian = DB::table('bagian')
                ->where('status','=','on')
                ->get();
            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;
                for($i=1; $i<=12;$i++){
                    $nilaiikpakontraktual = DB::table('ikpakontraktualbagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilai');
                    $nilaikontraktualakhir = $nilaiikpakontraktual*$BOBOT_KONTRAKTUAL;

                    $nilaiikpadeviasi = DB::table('ikpadeviasibagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaideviasiakhir = $nilaiikpadeviasi*$BOBOT_DEVIASI;

                    $nilaiikpapenyerapan = DB::table('ikpapenyerapanbagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpapenyerapan');
                    $nilaipenyerapanakhir = $nilaiikpapenyerapan*$BOBOT_PENYERAPAN;

                    $nilaiikpapenyelesaian = DB::table('ikpapenyelesaiantagihan')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('persen');
                    $nilaipenyelesaianakhir = $nilaiikpapenyelesaian*$BOBOT_PENYELESAIAN_TAGIHAN;

                    $nilaiikparevisi = DB::table('ikparevisibagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaiikparevisiakhir = $nilaiikparevisi*$BOBOT_REVISI;

                    $nilaiikpacaput = DB::table('ikparevisibagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaiikpacaputakhir = $nilaiikpacaput*$BOBOT_CAPUT;

                    $nilaitotal = $nilaipenyerapanakhir+$nilaideviasiakhir+$nilaipenyelesaianakhir+$nilaikontraktualakhir+$nilaiikparevisiakhir+$nilaiikpacaputakhir;

                    if ($kodesatker == '001012'){
                        $konversibobot = 0.9;
                    }else{
                        $konversibobot = 0.8;
                    }

                    $nilaitotalsetelahkonversi = $nilaitotal/$konversibobot;

                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'idbagian' => $idbagian,
                        'ikpapenyerapan' => $nilaipenyerapanakhir,
                        'ikpadeviasi' => $nilaideviasiakhir,
                        'ikpapenyelesaian' => $nilaipenyelesaianakhir,
                        'ikpakontraktual' => $nilaikontraktualakhir,
                        'ikparevisi' => $nilaiikparevisiakhir,
                        'ikpacaput' => $nilaiikpacaputakhir,
                        'ikpatotal' => $nilaitotalsetelahkonversi
                    );

                    //delete angka lama
                    DB::table('ikparekapbagian')
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikparekapbagian')->insert($datainsert);

                }
            }
        }
    }

    public function aksirekapikpabiro($tahunanggaran){
        $SETJEN_REVISI = 0.1;
        $SETJEN_DEVIASI = 0.15;
        $SETJEN_PENYERAPAN = 0.2;
        $SETJEN_KONTRAKTUAL = 0.1;
        $SETJEN_PENYELESAIAN_TAGIHAN = 0.1;
        $SETJEN_CAPUT = 0.25;
        $SETJEN_KKP = 0.1;

        $DEWAN_REVISI = 0.1;
        $DEWAN_DEVIASI = 0.05;
        $DEWAN_PENYERAPAN = 0.05;
        $DEWAN_KONTRAKTUAL = 0.25;
        $DEWAN_PENYELESAIAN_TAGIHAN = 0.25;
        $DEWAN_CAPUT = 0.1;
        $DEWAN_KKP = 0.2;
        //ambil data satker

        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            if ($kodesatker == "001012"){
                $BOBOT_REVISI = $SETJEN_REVISI;
                $BOBOT_DEVIASI = $SETJEN_DEVIASI;
                $BOBOT_PENYERAPAN = $SETJEN_PENYERAPAN;
                $BOBOT_KONTRAKTUAL = $SETJEN_KONTRAKTUAL;
                $BOBOT_PENYELESAIAN_TAGIHAN = $SETJEN_PENYELESAIAN_TAGIHAN;
                $BOBOT_CAPUT = $SETJEN_CAPUT;
                $BOBOT_KKP = $SETJEN_KKP;
            }else{
                $BOBOT_REVISI = $DEWAN_REVISI;
                $BOBOT_DEVIASI = $DEWAN_DEVIASI;
                $BOBOT_PENYERAPAN = $DEWAN_PENYERAPAN;
                $BOBOT_KONTRAKTUAL = $DEWAN_KONTRAKTUAL;
                $BOBOT_PENYELESAIAN_TAGIHAN = $DEWAN_PENYELESAIAN_TAGIHAN;
                $BOBOT_CAPUT = $DEWAN_CAPUT;
                $BOBOT_KKP = $DEWAN_KKP;
            }
            //ambil data bagian
            $databiro = DB::table('biro')
                ->where('status','=','on')
                ->get();
            foreach ($databiro as $db){
                $idbiro = $db->id;
                for($i=1; $i<=12;$i++){
                    $nilaiikpakontraktual = DB::table('ikpakontraktualbiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilai');
                    $nilaikontraktualakhir = $nilaiikpakontraktual*$BOBOT_KONTRAKTUAL;

                    $nilaiikpadeviasi = DB::table('ikpadeviasibiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaideviasiakhir = $nilaiikpadeviasi*$BOBOT_DEVIASI;

                    $nilaiikpapenyerapan = DB::table('ikpapenyerapanbiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpapenyerapan');
                    $nilaipenyerapanakhir = $nilaiikpapenyerapan*$BOBOT_PENYERAPAN;

                    $nilaiikpapenyelesaian = DB::table('ikpapenyelesaiantagihanbiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('persen');
                    $nilaipenyelesaianakhir = $nilaiikpapenyelesaian*$BOBOT_PENYELESAIAN_TAGIHAN;


                    $nilaiikparevisi = DB::table('ikparevisibiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaiikparevisiakhir = $nilaiikparevisi*$BOBOT_REVISI;

                    $nilaiikpacaput = DB::table('ikparevisibiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaiikpacaputakhir = $nilaiikpacaput*$BOBOT_CAPUT;

                    $nilaitotal = $nilaipenyerapanakhir+$nilaideviasiakhir+$nilaipenyelesaianakhir+$nilaikontraktualakhir+$nilaiikparevisiakhir+$nilaiikpacaputakhir;

                    if ($kodesatker == '001012'){
                        $konversibobot = 0.9;
                    }else{
                        $konversibobot = 0.8;
                    }

                    $nilaitotalsetelahkonversi = $nilaitotal/$konversibobot;

                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'ikpapenyerapan' => $nilaipenyerapanakhir,
                        'ikpadeviasi' => $nilaideviasiakhir,
                        'ikpapenyelesaian' => $nilaipenyelesaianakhir,
                        'ikpakontraktual' => $nilaikontraktualakhir,
                        'ikpatotal' => $nilaitotalsetelahkonversi
                    );

                    //delete angka lama
                    DB::table('ikparekapbiro')
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikparekapbiro')->insert($datainsert);

                }
            }
        }
    }
}
