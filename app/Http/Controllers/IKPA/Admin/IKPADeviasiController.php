<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Exports\ExportIkpaDeviasiBagian;
use App\Exports\ExportIkpaDeviasiBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaDeviasiBagian;
use App\Jobs\HitungIkpaDeviasiBiro;
use App\Models\IKPA\Admin\IKPADeviasiBiroModel;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPADeviasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        $databagian = DB::table('bagian')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpadeviasi',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function indexbiro(){
        $judul = 'Penilaian IKPA Deviasi Hal III DIPA';
        $databiro = DB::table('biro')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpadeviasibiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }

    public function getdataikpadeviasi(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPADeviasiModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpadeviasibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPADeviasiModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPADeviasiModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function getdataikpadeviasibiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPADeviasiBiroModel::with('birorelation')
                ->select(['ikpadeviasibiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPADeviasiBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function hitungikpadeviasibagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaDeviasiBagian($tahunanggaran));
        return redirect()->to('ikpadeviasi')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    public function hitungikpadeviasibiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaDeviasiBiro($tahunanggaran));
        return redirect()->to('ikpadeviasibiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpadeviasibagian(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaDeviasiBagian($tahunanggaran),'IkpaDeviasiBagian.xlsx');
    }

    function exportikpadeviasibiro(){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportIkpaDeviasiBiro($tahunanggaran),'IkpaDeviasiBiro.xlsx');
    }

    public function aksiperhitungandeviasibagian($tahunanggaran){
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
                $jenisbelanjadikelola = 0;
                if ($totalpagu !== null && $totalpagu !== '0' && (int)$totalpagu != 0){
                    $totalpagu51 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',51)
                        ->value('totalpagu');
                    if ($totalpagu51 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $totalpagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    if ($totalpagu52 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $totalpagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');
                    if ($totalpagu53 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $reratadeviasijenisbelanjaawal= 0;
                    for($i=1; $i<=12;$i++){
                        $pok = "pokikpa".$i;
                        $rencana51 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',51)
                            ->value('rencana');
                        $rencana52 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',52)
                            ->value('rencana');
                        $rencana53 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',53)
                            ->value('rencana');


                        //cek penyerapan
                        $r = "r".$i;
                        $penyerapan51 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',51)
                            ->value('realisasi');
                        $penyerapan52 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',52)
                            ->value('realisasi');
                        $penyerapan53 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbagian','=',$idbagian)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',53)
                            ->value('realisasi');

                        //echo "IDbagian: ".$idbagian." Rencana51: ".$rencana51." Rencana52: ".$rencana52." Rencana53: ".$rencana53;
                        //tentukan deviasi
                        $deviasi51 = abs($rencana51 - $penyerapan51);
                        $deviasi52 = abs($rencana52 - $penyerapan52);
                        $deviasi53 = abs($rencana53 - $penyerapan53);


                        //prosentase deviasi 51
                        if ($rencana51 == 0 && $penyerapan51 == 0){
                            $prosentasedeviasi51 = 0.00;
                        }else if ($rencana51 == 0 && $penyerapan51 > 0){
                            $prosentasedeviasi51 = 100.00;
                        }else if ($rencana51 != 0 && $penyerapan51 == 0){
                            $prosentasedeviasi51 = 100.00;
                        }else{
                            $prosentasedeviasi51 = ($deviasi51/$rencana51)*100;
                            if ($prosentasedeviasi51 <= 5){
                                $prosentasedeviasi51 = 0.00;
                            }else if ($prosentasedeviasi51 > 100){
                                $prosentasedeviasi51 = 100.00;
                            }else{
                                $prosentasedeviasi51 = $prosentasedeviasi51;
                            }
                        }

                        //tentukan prosentase deviasi
                        if ($rencana52 == 0 && $penyerapan52 == 0){
                            $prosentasedeviasi52 = 0.00;
                        }else if ($rencana52 == 0 && $penyerapan52 > 0){
                            $prosentasedeviasi52 = 100.00;
                        }else if ($rencana52 != 0 && $penyerapan52 == 0){
                            $prosentasedeviasi52 = 100.00;
                        }else{
                            $prosentasedeviasi52 = ($deviasi52/$rencana52)*100;
                            if ($prosentasedeviasi52 <= 5){
                                $prosentasedeviasi52 = 0.00;
                            }else if($prosentasedeviasi52 > 100){
                                $prosentasedeviasi52 = 100.00;
                            } else{
                                $prosentasedeviasi52 = $prosentasedeviasi52;
                            }
                        }

                        //tentukan prosentase deviasi 53
                        if ($rencana53 == 0 && $penyerapan53 == 0){
                            $prosentasedeviasi53 = 0.00;
                        }else if ($rencana53 == 0 && $penyerapan53 > 0){
                            $prosentasedeviasi53 = 100.00;
                        }else if ($rencana53 != 0 && $penyerapan53 == 0){
                            $prosentasedeviasi53 = 100.00;
                        }else{
                            $prosentasedeviasi53 = ($deviasi53/$rencana53)*100;
                            if ($prosentasedeviasi53 <= 5){
                                $prosentasedeviasi53 = 0.00;
                            }else if ($prosentasedeviasi53 > 100.00){
                                $prosentasedeviasi53 = 100.00;
                            } else{
                                $prosentasedeviasi53 = $prosentasedeviasi53;
                            }
                        }


                        $prosentasedeviasiseluruhjenis = $prosentasedeviasi51+$prosentasedeviasi52+$prosentasedeviasi53;
                        $reratadeviasijenisbelanja = $prosentasedeviasiseluruhjenis/$jenisbelanjadikelola;
                        $reratadeviasijenisbelanjaawal = $reratadeviasijenisbelanjaawal+$reratadeviasijenisbelanja;
                        $reratadeviasikumulatif = $reratadeviasijenisbelanjaawal/$i;
                        $nilaiikpa = 100-$reratadeviasikumulatif;


                        $datainsert = array(
                            'tahunanggaran' => $tahunanggaran,
                            'kdsatker' => $kodesatker,
                            'periode' => $i,
                            'idbagian' => $idbagian,
                            'idbiro' => $idbiro,
                            'rencana51' => $rencana51,
                            'rencana52' => $rencana52,
                            'rencana53' => $rencana53,
                            'penyerapan51' => $penyerapan51,
                            'penyerapan52' => $penyerapan52,
                            'penyerapan53' => $penyerapan53,
                            'deviasi51' => $deviasi51,
                            'deviasi52' => $deviasi52,
                            'deviasi53' => $deviasi53,
                            'prosentasedeviasi51' => $prosentasedeviasi51,
                            'prosentasedeviasi52' => $prosentasedeviasi52,
                            'prosentasedeviasi53' => $prosentasedeviasi53,
                            'prosentasedeviasiseluruhjenis' => $prosentasedeviasiseluruhjenis,
                            'jenisbelanjadikelola' => $jenisbelanjadikelola,
                            'reratadeviasijenisbelanja' => $reratadeviasijenisbelanja,
                            'reratadeviasikumulatif' => $reratadeviasikumulatif,
                            'nilaiikpa' => $nilaiikpa
                        );

                        //delete angka lama
                        DB::table('ikpadeviasibagian')
                            ->where('idbagian','=',$idbagian)
                            ->where('periode','=',$i)
                            ->where('kdsatker','=',$kodesatker)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->delete();

                        //insert data baru
                        DB::table('ikpadeviasibagian')->insert($datainsert);
                    }
                }
            }
        }
    }

    public function aksiperhitungandeviasibiro($tahunanggaran){
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
                $jenisbelanjadikelola = 0;
                if ($totalpagu !== null && $totalpagu !== '0' && (int)$totalpagu != 0){
                    $totalpagu51 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',51)
                        ->value('totalpagu');
                    if ($totalpagu51 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $totalpagu52 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',52)
                        ->value('totalpagu');
                    if ($totalpagu52 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $totalpagu53 = DB::table('laporanrealisasianggaranbac')
                        ->select([DB::raw('sum(paguanggaran) as totalpagu')])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('jenisbelanja','=',53)
                        ->value('totalpagu');
                    if ($totalpagu53 > 0){
                        $jenisbelanjadikelola = $jenisbelanjadikelola+1;
                    }
                    $reratadeviasijenisbelanjaawal= 0;
                    for($i=1; $i<=12;$i++){
                        $pok = "pokikpa".$i;
                        $rencana51 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',51)
                            ->value('rencana');
                        $rencana52 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',52)
                            ->value('rencana');
                        $rencana53 = DB::table('laporanrealisasianggaranbac')
                            ->select([DB::raw('sum('.$pok.') as rencana')])
                            ->where('idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',53)
                            ->value('rencana');


                        //cek penyerapan
                        $r = "r".$i;
                        $penyerapan51 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',51)
                            ->value('realisasi');
                        $penyerapan52 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',52)
                            ->value('realisasi');
                        $penyerapan53 = DB::table('laporanrealisasianggaranbac as a')
                            ->select([DB::raw('sum('.$r.') as realisasi')])
                            ->where('a.idbiro','=',$idbiro)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('a.kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',53)
                            ->value('realisasi');

                        //echo "IDbagian: ".$idbagian." Rencana51: ".$rencana51." Rencana52: ".$rencana52." Rencana53: ".$rencana53;
                        //tentukan deviasi
                        $deviasi51 = abs($rencana51 - $penyerapan51);
                        $deviasi52 = abs($rencana52 - $penyerapan52);
                        $deviasi53 = abs($rencana53 - $penyerapan53);


                        //prosentase deviasi 51
                        if ($rencana51 == 0 && $penyerapan51 == 0){
                            $prosentasedeviasi51 = 0.00;
                        }else if ($rencana51 == 0 && $penyerapan51 > 0){
                            $prosentasedeviasi51 = 100.00;
                        }else if ($rencana51 != 0 && $penyerapan51 == 0){
                            $prosentasedeviasi51 = 100.00;
                        }else{
                            $prosentasedeviasi51 = ($deviasi51/$rencana51)*100;
                            if ($prosentasedeviasi51 <= 5){
                                $prosentasedeviasi51 = 0.00;
                            }else if ($prosentasedeviasi51 > 100){
                                $prosentasedeviasi51 = 100.00;
                            }else{
                                $prosentasedeviasi51 = $prosentasedeviasi51;
                            }
                        }

                        //tentukan prosentase deviasi
                        if ($rencana52 == 0 && $penyerapan52 == 0){
                            $prosentasedeviasi52 = 0.00;
                        }else if ($rencana52 == 0 && $penyerapan52 > 0){
                            $prosentasedeviasi52 = 100.00;
                        }else if ($rencana52 != 0 && $penyerapan52 == 0){
                            $prosentasedeviasi52 = 100.00;
                        }else{
                            $prosentasedeviasi52 = ($deviasi52/$rencana52)*100;
                            if ($prosentasedeviasi52 <= 5){
                                $prosentasedeviasi52 = 0.00;
                            }else if($prosentasedeviasi52 > 100){
                                $prosentasedeviasi52 = 100.00;
                            } else{
                                $prosentasedeviasi52 = $prosentasedeviasi52;
                            }
                        }

                        //tentukan prosentase deviasi 53
                        if ($rencana53 == 0 && $penyerapan53 == 0){
                            $prosentasedeviasi53 = 0.00;
                        }else if ($rencana53 == 0 && $penyerapan53 > 0){
                            $prosentasedeviasi53 = 100.00;
                        }else if ($rencana53 != 0 && $penyerapan53 == 0){
                            $prosentasedeviasi53 = 100.00;
                        }else{
                            $prosentasedeviasi53 = ($deviasi53/$rencana53)*100;
                            if ($prosentasedeviasi53 <= 5){
                                $prosentasedeviasi53 = 0.00;
                            }else if ($prosentasedeviasi53 > 100.00){
                                $prosentasedeviasi53 = 100.00;
                            } else{
                                $prosentasedeviasi53 = $prosentasedeviasi53;
                            }
                        }


                        $prosentasedeviasiseluruhjenis = $prosentasedeviasi51+$prosentasedeviasi52+$prosentasedeviasi53;
                        $reratadeviasijenisbelanja = $prosentasedeviasiseluruhjenis/$jenisbelanjadikelola;
                        $reratadeviasijenisbelanjaawal = $reratadeviasijenisbelanjaawal+$reratadeviasijenisbelanja;
                        $reratadeviasikumulatif = $reratadeviasijenisbelanjaawal/$i;
                        $nilaiikpa = 100-$reratadeviasikumulatif;


                        $datainsert = array(
                            'tahunanggaran' => $tahunanggaran,
                            'kdsatker' => $kodesatker,
                            'periode' => $i,
                            'idbiro' => $idbiro,
                            'rencana51' => $rencana51,
                            'rencana52' => $rencana52,
                            'rencana53' => $rencana53,
                            'penyerapan51' => $penyerapan51,
                            'penyerapan52' => $penyerapan52,
                            'penyerapan53' => $penyerapan53,
                            'deviasi51' => $deviasi51,
                            'deviasi52' => $deviasi52,
                            'deviasi53' => $deviasi53,
                            'prosentasedeviasi51' => $prosentasedeviasi51,
                            'prosentasedeviasi52' => $prosentasedeviasi52,
                            'prosentasedeviasi53' => $prosentasedeviasi53,
                            'prosentasedeviasiseluruhjenis' => $prosentasedeviasiseluruhjenis,
                            'jenisbelanjadikelola' => $jenisbelanjadikelola,
                            'reratadeviasijenisbelanja' => $reratadeviasijenisbelanja,
                            'reratadeviasikumulatif' => $reratadeviasikumulatif,
                            'nilaiikpa' => $nilaiikpa
                        );

                        //delete angka lama
                        DB::table('ikpadeviasibiro')
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','=',$i)
                            ->where('kdsatker','=',$kodesatker)
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->delete();

                        //insert data baru
                        DB::table('ikpadeviasibiro')->insert($datainsert);
                    }
                }
            }
        }
    }

}
