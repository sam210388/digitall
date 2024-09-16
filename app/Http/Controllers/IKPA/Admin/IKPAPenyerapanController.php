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
                if ($totalpagu > 0){
                    $pagu51 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',51)
                        ->value('totalpagu');
                    if ($pagu51>0){
                        $porsipagu51 = ($pagu51/$totalpagu)*100;
                    }else{
                        $porsipagu51 = 0;
                    }
                    $pagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    if ($pagu52>0){
                        $porsipagu52 = ($pagu52/$totalpagu)*100;
                    }else{
                        $porsipagu52 = 0;
                    }
                    $pagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');
                    if ($pagu53>0){
                        $porsipagu53 = ($pagu53/$totalpagu)*100;
                    }else{
                        $porsipagu53 = 0;
                    }

                    $ikpatw1 = 0;
                    $ikpatw2 = 0;
                    $ikpatw3 = 0;
                    $ikpatw4 = 0;
                    for($i=1; $i<=12;$i++){

                        switch ($i){
                            case ($i == 1 || $i==2 || $i==3):
                                $prosentarget51 = 0.2;
                                $prosentarget52 = 0.2;
                                $prosentarget53 = 0.2;
                                $totaltarget = 0.2;
                                break;
                            case ($i == 4 || $i==5 || $i==6):
                                $prosentarget51 = 0.50;
                                $prosentarget52 = 0.50;
                                $prosentarget53 = 0.50;
                                $totaltarget = 0.50;
                                break;
                            case ($i == 7 || $i==8 || $i==9):
                                $prosentarget51 = 0.75;
                                $prosentarget52 = 0.75;
                                $prosentarget53 = 0.75;
                                $totaltarget = 0.75;
                                break;
                            case ($i == 10 || $i==11 || $i==12):
                                $prosentarget51 = 0.96;
                                $prosentarget52 = 0.96;
                                $prosentarget53 = 0.96;
                                $totaltarget = 0.96;
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
                        if ($pagu52 == 0){
                            $target52 = 0;
                        }else{
                            $target52 = $pagu52 * $prosentarget52;
                        }


                        //$totalnominaltarget = $target51 + $target52 + $target53;
                        //$persentarget = ($totalnominaltarget/$totalpagu)*100;
                        //echo $idbagian.". Periode: ".$i.". Nominal Target: ".$totalnominaltarget." dan Total PaguL:".$totalpagu." Persen Target".$persentarget."<br>";

                        //cek penyerapan
                        $rsd = "rsd".$i;
                        $penyerapan51 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',51)
                            ->value('realisasi');
                        if ($penyerapan51>0){
                            $prosentasepenyerapan51 = ($penyerapan51/$target51)*100;
                            if ($prosentasepenyerapan51 > 100){
                                $prosentasepenyerapan51 = 100;
                            }
                            $nilaikinerjapenyerapan51 = ($prosentasepenyerapan51*$porsipagu51)/100;

                        }else{
                            $prosentasepenyerapan51 = 0;
                            $nilaikinerjapenyerapan51 = 0;
                        }

                        $penyerapan52 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',52)
                            ->value('realisasi');
                        if ($penyerapan52>0){
                            $prosentasepenyerapan52 = ($penyerapan52/$target52)*100;
                            if ($prosentasepenyerapan52 > 100){
                                $prosentasepenyerapan52 = 100;
                            }
                            $nilaikinerjapenyerapan52 = ($prosentasepenyerapan52*$porsipagu52)/100;

                        }else{
                            $prosentasepenyerapan52 = 0;
                            $nilaikinerjapenyerapan52=0;
                        }

                        $penyerapan53 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',53)
                            ->value('realisasi');
                        if ($penyerapan53>0){
                            $prosentasepenyerapan53 = ($penyerapan53/$target53)*100;
                            if ($prosentasepenyerapan53 > 100){
                                $prosentasepenyerapan53 = 100;
                            }
                            $nilaikinerjapenyerapan53 = ($prosentasepenyerapan53*$porsipagu53)/100;

                        }else{
                            $prosentasepenyerapan53 = 0;
                            $nilaikinerjapenyerapan53 = 0;
                        }

                        //$persensdperiodeini = ($penyerapansdperiodeini/$totalpagu)*100;

                        /*
                        if ($persensdperiodeini > $persentarget){
                            $nilaikinerjapenyerapan = 100;
                        }else{
                            $nilaikinerjapenyerapan = ($persensdperiodeini/$persentarget)*100;
                        }
                        */


                        $nilaikinerjapenyerapantotal = $nilaikinerjapenyerapan51+$nilaikinerjapenyerapan52+$nilaikinerjapenyerapan53;

                        switch ($i){
                            case ($i == 1 || $i == 2):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapantotal;
                                break;
                            case ($i == 3):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapantotal;
                                $ikpatw1 = $ikpatw1+$nilaiikpapenyerapan;
                                break;
                            case($i == 4 || $i == 5):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapantotal)/2;
                                break;
                            case ($i == 6):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapantotal)/2;
                                $ikpatw2 = $ikpatw2+$nilaiikpapenyerapan;
                                break;
                            case ($i == 7 || $i == 8):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapantotal)/3;
                                break;
                            case ($i == 9):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapantotal)/3;
                                $ikpatw3 = $ikpatw3+$nilaiikpapenyerapan;
                                break;
                            case ($i == 10 || $i == 11):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapantotal)/4;
                                break;
                            case ($i == 12):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapantotal)/4;
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
                            'totalnominaltarget' => $target51+$target52+$target53,
                            'penyerapansdperiodeini' => $penyerapan51+$penyerapan52+$penyerapan53,
                            'penyerapan51' => $penyerapan51,
                            'penyerapan52' => $penyerapan52,
                            'penyerapan53' => $penyerapan53,
                            'targetpersenperiodeini' => $totaltarget,
                            'prosentasesdperiodeini' => (($penyerapan51+$penyerapan52+$penyerapan53)/($pagu51+$pagu52+$pagu53))*100,
                            'nilaikinerjapenyerapantw' => $nilaikinerjapenyerapantotal,
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
                    if ($pagu51>0){
                        $porsipagu51 = ($pagu51/$totalpagu)*100;
                    }else{
                        $porsipagu51 = 0;
                    }
                    $pagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    if ($pagu52>0){
                        $porsipagu52 = ($pagu52/$totalpagu)*100;
                    }else{
                        $porsipagu52 = 0;
                    }
                    $pagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');
                    if ($pagu53>0){
                        $porsipagu53 = ($pagu53/$totalpagu)*100;
                    }else{
                        $porsipagu53 = 0;
                    }

                    $ikpatw1 = 0;
                    $ikpatw2 = 0;
                    $ikpatw3 = 0;
                    $ikpatw4 = 0;
                    for($i=1; $i<=12;$i++){
                        switch ($i){
                            case ($i == 1 || $i==2 || $i==3):
                                $prosentarget51 = 0.2;
                                $prosentarget52 = 0.2;
                                $prosentarget53 = 0.2;
                                break;
                            case ($i == 4 || $i==5 || $i==6):
                                $prosentarget51 = 0.50;
                                $prosentarget52 = 0.50;
                                $prosentarget53 = 0.50;
                                break;
                            case ($i == 7 || $i==8 || $i==9):
                                $prosentarget51 = 0.75;
                                $prosentarget52 = 0.75;
                                $prosentarget53 = 0.75;
                                break;
                            case ($i == 10 || $i==11 || $i==12):
                                $prosentarget51 = 0.96;
                                $prosentarget52 = 0.96;
                                $prosentarget53 = 0.96;
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

                        if ($pagu52 == 0){
                            $target52 = 0;
                        }else{
                            $target52 = $pagu52 * $prosentarget52;
                        }

                        //$totalnominaltarget = $target51 + $target52 + $target53;
                        //$persentarget = ($totalnominaltarget/$totalpagu)*100;
                        //echo $idbagian.". Periode: ".$i.". Nominal Target: ".$totalnominaltarget." dan Total PaguL:".$totalpagu." Persen Target".$persentarget."<br>";

                        //cek penyerapan
                        $rsd = "rsd".$i;
                        $penyerapan51 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',51)
                            ->value('realisasi');
                        if ($penyerapan51>0){
                            $prosentasepenyerapan51 = ($penyerapan51/$target51)*100;
                            if ($prosentasepenyerapan51 > 100){
                                $prosentasepenyerapan51 = 100;
                            }
                            $nilaikinerjapenyerapan51 = ($prosentasepenyerapan51*$porsipagu51)/100;

                        }else{
                            $prosentasepenyerapan51 = 0;
                            $nilaikinerjapenyerapan51 = 0;
                        }

                        $penyerapan52 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',52)
                            ->value('realisasi');
                        if ($penyerapan52>0){
                            $prosentasepenyerapan52 = ($penyerapan52/$target52)*100;
                            if ($prosentasepenyerapan52 > 100){
                                $prosentasepenyerapan52 = 100;
                            }
                            $nilaikinerjapenyerapan52 = ($prosentasepenyerapan52*$porsipagu52)/100;

                        }else{
                            $prosentasepenyerapan52 = 0;
                            $nilaikinerjapenyerapan52=0;
                        }

                        $penyerapan53 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$rsd.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('a.jenisbelanja','=',53)
                            ->value('realisasi');
                        if ($penyerapan53>0){
                            $prosentasepenyerapan53 = ($penyerapan53/$target53)*100;
                            if ($prosentasepenyerapan53 > 100){
                                $prosentasepenyerapan53 = 100;
                            }
                            $nilaikinerjapenyerapan53 = ($prosentasepenyerapan53*$porsipagu53)/100;

                        }else{
                            $prosentasepenyerapan53 = 0;
                            $nilaikinerjapenyerapan53 = 0;
                        }

                        //$persensdperiodeini = ($penyerapansdperiodeini/$totalpagu)*100;

                        /*
                        if ($persensdperiodeini > $persentarget){
                            $nilaikinerjapenyerapan = 100;
                        }else{
                            $nilaikinerjapenyerapan = ($persensdperiodeini/$persentarget)*100;
                        }
                        */
                        $nilaikinerjapenyerapantotal = $nilaikinerjapenyerapan51+$nilaikinerjapenyerapan52+$nilaikinerjapenyerapan53;


                        switch ($i){
                            case ($i == 1 || $i == 2):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapantotal;
                                break;
                            case ($i == 3):
                                $nilaiikpapenyerapan = $nilaikinerjapenyerapantotal;
                                $ikpatw1 = $ikpatw1+$nilaiikpapenyerapan;
                                break;
                            case($i == 4 || $i == 5):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapantotal)/2;
                                break;
                            case ($i == 6):
                                $nilaiikpapenyerapan = ($ikpatw1+$nilaikinerjapenyerapantotal)/2;
                                $ikpatw2 = $ikpatw2+$nilaiikpapenyerapan;
                                break;
                            case ($i == 7 || $i == 8):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapantotal)/3;
                                break;
                            case ($i == 9):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$nilaikinerjapenyerapantotal)/3;
                                $ikpatw3 = $ikpatw3+$nilaiikpapenyerapan;
                                break;
                            case ($i == 10 || $i == 11):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapantotal)/4;
                                break;
                            case ($i == 12):
                                $nilaiikpapenyerapan = ($ikpatw1+$ikpatw2+$ikpatw3+$nilaikinerjapenyerapantotal)/4;
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
                            'totalnominaltarget' => 0,
                            'penyerapansdperiodeini' => 0,
                            'penyerapan51' => $penyerapan51,
                            'penyerapan52' => $penyerapan52,
                            'penyerapan53' => $penyerapan53,
                            'targetpersenperiodeini' => 0,
                            'prosentasesdperiodeini' => 0,
                            'nilaikinerjapenyerapantw' => $nilaikinerjapenyerapantotal,
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
