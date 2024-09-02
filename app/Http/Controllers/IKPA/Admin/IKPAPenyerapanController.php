<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Exports\ExportIkpaPenyerapanBagian;
use App\Exports\ExportIkpaPenyerapanBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaPenyerapanBagian;
use App\Jobs\HitungIkpaPenyerapanBiro;
use App\Models\IKPA\Admin\IkpaPenyerapanBiroModel;
use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\Switch_;
use Yajra\DataTables\DataTables;

class IKPAPenyerapanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Penyerapan';
        $databagian = DB::table('bagian')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpapenyerapan',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function indexbiro(){
        $judul = 'Penilaian IKPA Penyerapan';
        $databiro = DB::table('biro')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpapenyerapanbiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }

    public function getdataikpapenyerapanbagian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyerapanModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpapenyerapanbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran);
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IkpaPenyerapanModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IkpaPenyerapanModel $id) {
                    return $id->birorelation->uraianbiro;
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function getdataikpapenyerapanbiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyerapanBiroModel::with('birorelation')
                ->select(['ikpapenyerapanbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran);
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IkpaPenyerapanBiroModel $id) {
                    return $id->birorelation->uraianbiro;
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function hitungikpapenyerapanbagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaPenyerapanBagian($tahunanggaran));
        return redirect()->to('ikpapenyerapan')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    public function hitungikpapenyerapanbiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaPenyerapanBiro($tahunanggaran));
        return redirect()->to('ikpapenyerapanbiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpapenyerapanbagian(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaPenyerapanBagian($tahunanggaran),'IkpaPenyerapanBagian.xlsx');
    }

    function exportikpapenyerapanbiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaPenyerapanBiro($tahunanggaran),'IkpaPenyerapanBiro.xlsx');
    }

    public function aksiperhitunganikpapenyerapanbagian($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databagian = DB::table('bagian')->where('status','=','on')->get();
            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;

                $totalpagu = DB::table('laporanrealisasianggaranbac')
                    ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                    ->where('idbagian','=',$idbagian)
                    ->where('tahunanggaran','=',$tahunanggaran)
                    ->where('kodesatker','=',$kodesatker)
                    ->value('totalpagu');
                if ($totalpagu != null){
                    $pagu51 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',51)
                        ->value('totalpagu');
                    $pagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    $pagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');


                    $ikpatw1 = 0;
                    $ikpatw2 = 0;
                    $ikpatw3 = 0;
                    $ikpatw4 = 0;
                    for($i=1; $i<=12;$i++){
                        switch ($i){
                            case ($i == 1 || $i==2 || $i==3):
                                $prosentarget51 = 0.2;
                                $prosentarget52 = 0.2;
                                $prosentarget53 = 0.15;
                                break;
                            case ($i == 4 || $i==5 || $i==6):
                                $prosentarget51 = 0.50;
                                $prosentarget52 = 0.50;
                                $prosentarget53 = 0.40;
                                break;
                            case ($i == 7 || $i==8 || $i==9):
                                $prosentarget51 = 0.75;
                                $prosentarget52 = 0.75;
                                $prosentarget53 = 0.70;
                                break;
                            case ($i == 10 || $i==11 || $i==12):
                                $prosentarget51 = 0.95;
                                $prosentarget52 = 0.95;
                                $prosentarget53 = 0.95;
                        }

                        if ($pagu51 == 0){
                            $target51 = 0;
                        }else{
                            $target51 = $pagu51*$prosentarget51;
                        }

                        if ($pagu53 == 0){
                            $target53 = 0;
                        }else{
                            $target53 = $pagu53*$prosentarget53;
                        }

                        $target52 = $pagu52 * $prosentarget52;

                        $totalnominaltarget = $target51 + $target52 + $target53;
                        $persentarget = ($totalnominaltarget/$totalpagu)*100;
                        //echo $idbagian.". Periode: ".$i.". Nominal Target: ".$totalnominaltarget." dan Total PaguL:".$totalpagu." Persen Target".$persentarget."<br>";

                        //cek penyerapan
                        $rsd = "rsd".$i;
                        $penyerapansdperiodeini = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->value('realisasi');

                        $persensdperiodeini = ($penyerapansdperiodeini/$totalpagu)*100;


                        if ($persensdperiodeini > $persentarget){
                            $nilaikinerjapenyerapan = 100;
                        }else{
                            $nilaikinerjapenyerapan = ($persensdperiodeini/$persentarget)*100;
                        }


                        switch ($i){
                            case ($i == 1 || $i == 2):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapan;
                                break;
                            case ($i == 3):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapan;
                                $ikpatw1 = $ikpatw1+$nilaiikpapenyerapan;
                                break;
                            case($i == 4 || $i == 5):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapan)/2;
                                break;
                            case ($i == 6):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapan)/2;
                                $ikpatw2 = $ikpatw2+$nilaiikpapenyerapan;
                                break;
                            case ($i == 7 || $i == 8):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapan)/3;
                                break;
                            case ($i == 9):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapan)/3;
                                $ikpatw3 = $ikpatw3+$nilaiikpapenyerapan;
                                break;
                            case ($i == 10 || $i == 11):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapan)/4;
                                break;
                            case ($i == 12):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapan)/4;
                                break;
                        }


                        $datainsert = array(
                            'tahunanggaran' => $tahunanggaran,
                            'kdsatker' => $kodesatker,
                            'idbagian' => $idbagian,
                            'idbiro' => $idbiro,
                            'periode' => $i,
                            'pagu51' => $pagu51,
                            'pagu52' => $pagu52,
                            'pagu53' => $pagu53,
                            'nominaltarget51' => $target51,
                            'nominaltarget52' => $target52,
                            'nominaltarget53' => $target53,
                            'totalpagu' => $totalpagu,
                            'totalnominaltarget' => $totalnominaltarget,
                            'penyerapansdperiodeini' => $penyerapansdperiodeini,
                            'targetpersenperiodeini' => $persentarget,
                            'prosentasesdperiodeini' => $persensdperiodeini,
                            'nilaikinerjapenyerapantw' => $nilaikinerjapenyerapan,
                            'nilaiikpapenyerapan' => $nilaiikpapenyerapan
                        );

                        //delete angka lama
                        DB::table('ikpapenyerapanbagian')
                            ->where('idbagian','=',$idbagian)
                            ->where('periode','=',$i)
                            ->where('kdsatker','=',$kodesatker)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->delete();

                        //insert data baru
                        DB::table('ikpapenyerapanbagian')->insert($datainsert);
                    }
                }
            }
        }
    }

    public function aksiperhitunganikpapenyerapanbiro($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databiro = DB::table('biro')->where('status','=','on')->get();
            foreach ($databiro as $db){
                $idbiro = $db->id;

                $totalpagu = DB::table('laporanrealisasianggaranbac')
                    ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                    ->where('idbiro','=',$idbiro)
                    ->where('tahunanggaran','=',$tahunanggaran)
                    ->where('kodesatker','=',$kodesatker)
                    ->value('totalpagu');
                if ($totalpagu !== null && $totalpagu !== '0' && (int)$totalpagu != 0){
                    $pagu51 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',51)
                        ->value('totalpagu');
                    $pagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    $pagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');


                    $ikpatw1 = 0;
                    $ikpatw2 = 0;
                    $ikpatw3 = 0;
                    $ikpatw4 = 0;
                    for($i=1; $i<=12;$i++){
                        switch ($i){
                            case ($i == 1 || $i==2 || $i==3):
                                $prosentarget51 = 0.2;
                                $prosentarget52 = 0.2;
                                $prosentarget53 = 0.15;
                                break;
                            case ($i == 4 || $i==5 || $i==6):
                                $prosentarget51 = 0.50;
                                $prosentarget52 = 0.50;
                                $prosentarget53 = 0.40;
                                break;
                            case ($i == 7 || $i==8 || $i==9):
                                $prosentarget51 = 0.75;
                                $prosentarget52 = 0.75;
                                $prosentarget53 = 0.70;
                                break;
                            case ($i == 10 || $i==11 || $i==12):
                                $prosentarget51 = 0.95;
                                $prosentarget52 = 0.95;
                                $prosentarget53 = 0.95;
                        }

                        if ($pagu51 == 0){
                            $target51 = 0;
                        }else{
                            $target51 = $pagu51*$prosentarget51;
                        }

                        if ($pagu53 == 0){
                            $target53 = 0;
                        }else{
                            $target53 = $pagu53*$prosentarget53;
                        }

                        $target52 = $pagu52 * $prosentarget52;

                        $totalnominaltarget = $target51 + $target52 + $target53;
                        $persentarget = ($totalnominaltarget/$totalpagu)*100;
                        //echo $idbagian.". Periode: ".$i.". Nominal Target: ".$totalnominaltarget." dan Total PaguL:".$totalpagu." Persen Target".$persentarget."<br>";

                        //cek penyerapan
                        $rsd = "rsd".$i;
                        $penyerapansdperiodeini = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->value('realisasi');

                        $persensdperiodeini = ($penyerapansdperiodeini/$totalpagu)*100;


                        if ($persensdperiodeini > $persentarget){
                            $nilaikinerjapenyerapan = 100;
                        }else{
                            $nilaikinerjapenyerapan = ($persensdperiodeini/$persentarget)*100;
                        }


                        switch ($i){
                            case ($i == 1 || $i == 2):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapan;
                                break;
                            case ($i == 3):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapan;
                                $ikpatw1 = $ikpatw1+$nilaiikpapenyerapan;
                                break;
                            case($i == 4 || $i == 5):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapan)/2;
                                break;
                            case ($i == 6):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapan)/2;
                                $ikpatw2 = $ikpatw2+$nilaiikpapenyerapan;
                                break;
                            case ($i == 7 || $i == 8):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapan)/3;
                                break;
                            case ($i == 9):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapan)/3;
                                $ikpatw3 = $ikpatw3+$nilaiikpapenyerapan;
                                break;
                            case ($i == 10 || $i == 11):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapan)/4;
                                break;
                            case ($i == 12):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapan)/4;
                                break;
                        }


                        $datainsert = array(
                            'tahunanggaran' => $tahunanggaran,
                            'kdsatker' => $kodesatker,
                            'idbiro' => $idbiro,
                            'periode' => $i,
                            'pagu51' => $pagu51,
                            'pagu52' => $pagu52,
                            'pagu53' => $pagu53,
                            'nominaltarget51' => $target51,
                            'nominaltarget52' => $target52,
                            'nominaltarget53' => $target53,
                            'totalpagu' => $totalpagu,
                            'totalnominaltarget' => $totalnominaltarget,
                            'penyerapansdperiodeini' => $penyerapansdperiodeini,
                            'targetpersenperiodeini' => $persentarget,
                            'prosentasesdperiodeini' => $persensdperiodeini,
                            'nilaikinerjapenyerapantw' => $nilaikinerjapenyerapan,
                            'nilaiikpapenyerapan' => $nilaiikpapenyerapan
                        );

                        //delete angka lama
                        DB::table('ikpapenyerapanbiro')
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','=',$i)
                            ->where('kdsatker','=',$kodesatker)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->delete();

                        //insert data baru
                        DB::table('ikpapenyerapanbiro')->insert($datainsert);
                    }
                }
            }
        }
    }

}
