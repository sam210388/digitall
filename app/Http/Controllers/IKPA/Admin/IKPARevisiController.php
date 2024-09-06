<?php

namespace App\Http\Controllers\IKPA\Admin;


use App\Exports\ExportIkpaKontraktualBiro;
use App\Exports\ExportIKPARevisiBagian;
use App\Exports\ExportIKPARevisiBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaRevisi;
use App\Jobs\HitungIkpaRevisiBiro;
use App\Models\IKPA\Admin\IKPARevisiBagianModel;
use App\Models\IKPA\Admin\IKPARevisiBiroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPARevisiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Revisi Bagian';
        $databagian = DB::table('bagian')
            ->where('status','=','on')
            ->get();
        return view('IKPA.Admin.ikparevisibagian',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }


    public function hitungikparevisibagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaRevisi($tahunanggaran));
        return redirect()->to('ikparevisibagian')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikparevisibagian(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIKPARevisiBagian($tahunanggaran),'IKPARevisiBagian.xlsx');
    }

    public function getdataikparevisibagian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPARevisiBagianModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikparevisibagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPARevisiBagianModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPARevisiBagianModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function aksiperhitunganikparevisibagian($tahunanggaran){
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
                $totalnilaiikpa = 0;
                $jumlahrevisikemenkeusmt1 = 0;
                $jumlahrevisikemenkeusmt2 = 0;
                $jumlahtotalrevisikemenkeu = 0;
                $nilairevisiikpakemenkeusmt1 = 0;
                $nilairevisipokawal = 0;

                for($i=1; $i<=12;$i++){
                    $jumlahrevisipok = DB::table('ikpadetilrevisi')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('bulanpengesahan','=',$i)
                        ->where('kewenanganrevisi','=',"Revisi POK")
                        ->where('kodesatker','=',$kodesatker)
                        ->count();
                    if ($jumlahrevisipok > 0){
                        $nilairevisipokbulanan = (1/$jumlahrevisipok)*100;
                    }else{
                        $nilairevisipokbulanan = 100.00;
                    }
                    $nilairevisipokawal = $nilairevisipokawal + $nilairevisipokbulanan;
                    $nilairevisiikpapok = $nilairevisipokawal/$i;


                    //menghitung revisi kemenkeu
                    $jumlahrevisikemenkeubulanan = DB::table('ikpadetilrevisi')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('bulanpengesahan','=',$i)
                        ->where('kewenanganrevisi','=',"Revisi Kemenkeu")
                        ->where('kodesatker','=',$kodesatker)
                        ->count();
                    if ($i<=5){
                        $jumlahrevisikemenkeusmt1 = $jumlahrevisikemenkeusmt1 + $jumlahrevisikemenkeubulanan;
                        $jumlahtotalrevisikemenkeu = $jumlahrevisikemenkeusmt1;
                        if ($jumlahrevisikemenkeusmt1 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt1 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = $nilairevisikemenkeu;
                    }else if ($i == 6){
                        if ($jumlahrevisikemenkeusmt1 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt1 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = $nilairevisikemenkeu;
                        $nilairevisiikpakemenkeusmt1 = $nilairevisiikpakemenkeu;
                    }
                    else{
                        $jumlahrevisikemenkeusmt2 = $jumlahrevisikemenkeusmt2+$jumlahrevisikemenkeubulanan;
                        $jumlahtotalrevisikemenkeu = $jumlahrevisikemenkeusmt1+$jumlahrevisikemenkeusmt2;
                        if ($jumlahrevisikemenkeusmt2 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt2 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = ($nilairevisiikpakemenkeusmt1+$nilairevisikemenkeu)/2;
                    }


                    $nilaiikpabulanan = (0.4*$nilairevisiikpapok)+(0.6*$nilairevisiikpakemenkeu);
                    if ($nilaiikpabulanan>100){
                        $nilaiikpabulanan = 100;
                    }
                    $nilaiikpaakhir = $nilaiikpabulanan;


                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'idbagian' => $idbagian,
                        'jumlahrevisipok' => $jumlahrevisipok,
                        'jumlahrevisikemenkeu' => $jumlahrevisikemenkeubulanan,
                        'nilaiikpapok' => $nilairevisiikpapok,
                        'nilaiikpakemenkeu' => $nilairevisiikpakemenkeu,
                        'nilaiikpabulanan' => $nilaiikpabulanan,
                        'nilaiikpa' => $nilaiikpaakhir

                    );

                    //delete angka lama
                    DB::table('ikparevisibagian')
                        ->where('idbiro','=',$idbiro)
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikparevisibagian')->insert($datainsert);

                }
            }
        }
    }


    //BIRO
    public function indexbiro(){
        $judul = 'IKPA Revisi';
        $databiro = DB::table('biro')
            ->where('status','=','on')
            ->get();
        return view('IKPA.Admin.ikparevisibiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }


    public function hitungikparevisibiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaRevisiBiro($tahunanggaran));
        return redirect()->to('ikparevisibiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikparevisibiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIKPARevisiBiro($tahunanggaran),'IKPARevisiBiro.xlsx');
    }

    public function getdataikparevisibiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPARevisiBiroModel::with('birorelation')
                ->select(['ikparevisibiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPARevisiBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function aksiperhitunganikparevisibiro($tahunanggaran){
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
                $totalnilaiikpa = 0;
                $jumlahrevisikemenkeusmt1 = 0;
                $jumlahrevisikemenkeusmt2 = 0;
                $jumlahtotalrevisikemenkeu = 0;
                $nilairevisiikpakemenkeusmt1 = 0;
                $nilairevisipokawal = 0;

                for($i=1; $i<=12;$i++){
                    $jumlahrevisipok = DB::table('ikpadetilrevisi')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('bulanpengesahan','=',$i)
                        ->where('kewenanganrevisi','=',"Revisi POK")
                        ->where('kodesatker','=',$kodesatker)
                        ->count();
                    if ($jumlahrevisipok > 0){
                        $nilairevisipokbulanan = (1/$jumlahrevisipok)*100;
                    }else{
                        $nilairevisipokbulanan = 100.00;
                    }
                    $nilairevisipokawal = $nilairevisipokawal + $nilairevisipokbulanan;
                    $nilairevisiikpapok = $nilairevisipokawal/$i;


                    $jumlahrevisikemenkeubulanan = DB::table('ikpadetilrevisi')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('bulanpengesahan','=',$i)
                        ->where('kewenanganrevisi','=',"Revisi Kemenkeu")
                        ->where('kodesatker','=',$kodesatker)
                        ->count();

                    if ($i<=5){
                        $jumlahrevisikemenkeusmt1 = $jumlahrevisikemenkeusmt1 + $jumlahrevisikemenkeubulanan;
                        $jumlahtotalrevisikemenkeu = $jumlahrevisikemenkeusmt1;
                        if ($jumlahrevisikemenkeusmt1 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt1 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = $nilairevisikemenkeu;
                    }else if ($i == 6){
                        if ($jumlahrevisikemenkeusmt1 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt1 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = $nilairevisikemenkeu;
                        $nilairevisiikpakemenkeusmt1 = $nilairevisiikpakemenkeu;
                    }
                    else{
                        $jumlahrevisikemenkeusmt2 = $jumlahrevisikemenkeusmt2+$jumlahrevisikemenkeubulanan;
                        $jumlahtotalrevisikemenkeu = $jumlahrevisikemenkeusmt1+$jumlahrevisikemenkeusmt2;
                        if ($jumlahrevisikemenkeusmt2 <2){
                            $nilairevisikemenkeu = 110;
                        }else if ($jumlahrevisikemenkeusmt2 == 2){
                            $nilairevisikemenkeu = 100;
                        }else{
                            $nilairevisikemenkeu = 50;
                        }
                        $nilairevisiikpakemenkeu = ($nilairevisiikpakemenkeusmt1+$nilairevisikemenkeu)/2;
                    }


                    $nilaiikpabulanan = (0.4*$nilairevisiikpapok)+(0.6*$nilairevisiikpakemenkeu);
                    if ($nilaiikpabulanan>100){
                        $nilaiikpabulanan = 100;
                    }
                    $nilaiikpaakhir = $nilaiikpabulanan;


                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'jumlahrevisipok' => $jumlahrevisipok,
                        'jumlahrevisikemenkeu' => $jumlahrevisikemenkeubulanan,
                        'nilaiikpapok' => $nilairevisiikpapok,
                        'nilaiikpakemenkeu' => $nilairevisikemenkeu,
                        'nilaiikpabulanan' => $nilaiikpabulanan,
                        'nilaiikpa' => $nilaiikpaakhir

                    );

                    //delete angka lama
                    DB::table('ikparevisibiro')
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikparevisibiro')->insert($datainsert);

                }
            }
        }
    }
}
