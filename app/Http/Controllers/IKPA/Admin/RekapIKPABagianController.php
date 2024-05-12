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
        //BOBOT SETJEN
        $SETJEN_PENYERAPAN = 0.25;
        $SETJEN_DEVIASI = 0.1;
        $SETJEN_PENYELESAIAN = 0.15;
        $SETJEN_KONTRAKTUAL = 0.1;
        $SETJEN_REVISI = 0.1;
        $SETJEN_CAPUT = 0.3;

        //BOBOT DEWAN
        $DEWAN_PENYERAPAN = 0.05;
        $DEWAN_DEVIASI = 0.05;
        $DEWAN_PENYELESAIAN = 0.35;
        $DEWAN_KONTRAKTUAL = 0.35;
        $DEWAN_REVISI = 0.1;
        $DEWAN_CAPUT = 0.1;
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databagian = DB::table('bagian')
                ->where('status','=','on')
                ->get();
            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;
                $BOBOT_PENYERAPAN = 0.25;
                $BOBOT_PENYELESAIAN_TAGIHAN = 0.15;
                for($i=1; $i<=12;$i++){
                    if (in_array($idbiro,[677,688,605,728])){
                        $BOBOT_DEVIASI = 0.1;
                        $BOBOT_KONTRAKTUAL = 0.1;
                        $nilaiikpakontraktual = DB::table('ikpakontraktualbagian')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('periode','=',$i)
                            ->value('nilai');
                        $nilaikontraktualakhir = $nilaiikpakontraktual*$BOBOT_KONTRAKTUAL;
                    }else{
                        $BOBOT_DEVIASI = 0.2;
                        $nilaikontraktualakhir = 0;
                    }
                    $nilaiikpadeviasi = DB::table('ikpadeviasibagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaideviasiakhir = $nilaiikpadeviasi*$BOBOT_DEVIASI;

                    $nilaiikpapenyerapan = DB::table('ikpapenyerapanbagian')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->value('nilaiikpapenyerapan');
                    $nilaipenyerapanakhir = $nilaiikpapenyerapan*$BOBOT_PENYERAPAN;
                    $nilaiikpapenyelesaian = DB::table('ikpapenyelesaiantagihan')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->value('persen');
                    $nilaipenyelesaianakhir = $nilaiikpapenyelesaian*$BOBOT_PENYELESAIAN_TAGIHAN;

                    $nilaitotal = $nilaipenyerapanakhir+$nilaideviasiakhir+$nilaipenyelesaianakhir+$nilaikontraktualakhir;

                    $nilaitotalsetelahkonversi = $nilaitotal/0.6;

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
        //BOBOT SETJEN
        $SETJEN_PENYERAPAN = 0.25;
        $SETJEN_DEVIASI = 0.1;
        $SETJEN_PENYELESAIAN = 0.15;
        $SETJEN_KONTRAKTUAL = 0.1;
        $SETJEN_REVISI = 0.1;
        $SETJEN_CAPUT = 0.3;

        //BOBOT DEWAN
        $DEWAN_PENYERAPAN = 0.05;
        $DEWAN_DEVIASI = 0.05;
        $DEWAN_PENYELESAIAN = 0.35;
        $DEWAN_KONTRAKTUAL = 0.35;
        $DEWAN_REVISI = 0.1;
        $DEWAN_CAPUT = 0.1;
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databiro = DB::table('biro')
                ->where('status','=','on')
                ->get();
            foreach ($databiro as $db){
                $idbiro = $db->id;
                $BOBOT_PENYERAPAN = 0.25;
                $BOBOT_PENYELESAIAN_TAGIHAN = 0.15;
                for($i=1; $i<=12;$i++){
                    if (in_array($idbiro,[677,688,605,728])){
                        $BOBOT_DEVIASI = 0.1;
                        $BOBOT_KONTRAKTUAL = 0.1;
                        $nilaiikpakontraktual = DB::table('ikpakontraktualbiro')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','=',$i)
                            ->value('nilai');
                        $nilaikontraktualakhir = $nilaiikpakontraktual*$BOBOT_KONTRAKTUAL;
                    }else{
                        $BOBOT_DEVIASI = 0.2;
                        $nilaikontraktualakhir = 0;
                    }
                    $nilaiikpadeviasi = DB::table('ikpadeviasibiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->value('nilaiikpa');
                    $nilaideviasiakhir = $nilaiikpadeviasi*$BOBOT_DEVIASI;

                    $nilaiikpapenyerapan = DB::table('ikpapenyerapanbiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->value('nilaiikpapenyerapan');
                    $nilaipenyerapanakhir = $nilaiikpapenyerapan*$BOBOT_PENYERAPAN;
                    $nilaiikpapenyelesaian = DB::table('ikpapenyelesaiantagihanbiro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->value('persen');
                    $nilaipenyelesaianakhir = $nilaiikpapenyelesaian*$BOBOT_PENYELESAIAN_TAGIHAN;

                    $nilaitotal = $nilaipenyerapanakhir+$nilaideviasiakhir+$nilaipenyelesaianakhir+$nilaikontraktualakhir;

                    $nilaitotalsetelahkonversi = $nilaitotal/0.6;

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
