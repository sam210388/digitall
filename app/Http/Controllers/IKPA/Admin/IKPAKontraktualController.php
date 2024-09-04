<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Exports\ExportIkpaKontraktualBagian;
use App\Exports\ExportIkpaKontraktualBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaKontraktualBagian;
use App\Jobs\HitungIkpaKontraktualBiro;
use App\Models\IKPA\Admin\IKPAKontraktualBiroModel;
use App\Models\IKPA\Admin\IKPAKontraktualModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPAKontraktualController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Kontraktual';
        $databagian = DB::table('bagian')
            ->where('status','=','on')
            ->whereIn('idbiro',[677,688,605,728])
            ->get();
        return view('IKPA.Admin.ikpakontraktual',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function indexbiro(){
        $judul = 'IKPA Kontraktual';
        $databiro = DB::table('biro')
            ->where('status','=','on')
            ->whereIn('id',[677,688,605,728])
            ->get();
        return view('IKPA.Admin.ikpakontraktualbiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }

    public function hitungikpakontraktualbagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaKontraktualBagian($tahunanggaran));
        return redirect()->to('ikpakontraktual')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    public function hitungikpakontraktualbiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaKontraktualBiro($tahunanggaran));
        return redirect()->to('ikpakontraktualbiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpakontraktualbagian(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaKontraktualBagian($tahunanggaran),'IKPAKontraktualBagian.xlsx');
    }

    function exportikpakontraktualbiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaKontraktualBiro($tahunanggaran),'IKPAKontraktualBiro.xlsx');
    }

    public function getdataikpakontraktualbagian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPAKontraktualModel::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpakontraktualbagian.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IKPAKontraktualModel $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IKPAKontraktualModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function getdataikpakontraktualbiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IKPAKontraktualBiroModel::with('birorelation')
                ->select(['ikpakontraktualbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kodesatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IKPAKontraktualBiroModel $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function aksiperhitunganikpakontraktual($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databagian = DB::table('bagian')
                ->where('status','=','on')
                ->whereIn('idbiro',[677,688,605,728])
                ->get();
            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;
                for($i=1; $i<=12;$i++){
                    $jumlahkontrak = DB::table('ikpadetilkontraktual')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','<=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->count();
                    if ($jumlahkontrak>0){
                        $jumlahkontraktepatwaktu = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('periode','<=',$i)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('status','=','TEPAT WAKTU')
                            ->count();
                        $nilaikomponenketepatanwaktu = ($jumlahkontraktepatwaktu/$jumlahkontrak)*100;

                        //jumlah kontrak akselerasi
                        $jumlahkontraktw1 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('periode','<=',3)
                            ->where('kodesatker','=',$kodesatker)
                            ->count();
                        $jumlahkontrakakselerasi = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('kodesatker','=',$kodesatker)
                            ->whereYear('tanggal_kontrak', '=', $tahunanggaran - 1)
                            ->whereMonth('tanggal_kontrak', '=', 12)
                            ->count();
                        if ($jumlahkontraktw1 >0){
                            $nilaikomponenakselerasi = (($jumlahkontrakakselerasi*120)+(($jumlahkontraktw1-$jumlahkontrakakselerasi)*100))/$jumlahkontraktw1;
                        }else{
                            $nilaikomponenakselerasi = 0;
                        }


                        //jumlah kontrak 53
                        //jumlah kontrak akselerasi
                        $jumlahkontrak53 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where('kodesatker','=',$kodesatker)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->whereNotNull('tanggal_penyelesaian')
                            ->count();
                        $jumlahkontrakselesaitw1 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->whereMonth('tanggal_penyelesaian', '<=', 3)
                            ->count();
                        $nilaikontrakselesaitw1 = $jumlahkontrakselesaitw1*100;
                        $jumlahkontrakselesaitw2 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 3);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 6);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw2 = $jumlahkontrakselesaitw2*90;

                        $jumlahkontrakselesaitw3 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 6);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 9);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw3 = $jumlahkontrakselesaitw3 * 80;

                        $jumlahkontrakselesaitw4 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbagian','=',$idbagian)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 9);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 12);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw4 = $jumlahkontrakselesaitw4 * 70;
                        if ($jumlahkontrak53 > 0){
                            $nilaikomponen53 = ($nilaikontrakselesaitw1+$nilaikontrakselesaitw2+$nilaikontrakselesaitw3+$nilaikontrakselesaitw4)/$jumlahkontrak53;
                        }else{
                            $nilaikomponen53 = 100;
                        }

                        //hitung nilai ikpa
                        $nilai = ($nilaikomponenketepatanwaktu*0.4)+($nilaikomponenakselerasi*0.3)+($nilaikomponen53*0.3);
                        if($nilai > 100){
                            $nilai = 100.00;
                        }
                    }else{
                        $jumlahkontrak = 0;
                        $nilaikomponenketepatanwaktu = 0;
                        $jumlahkontraktw1 = 0;
                        $jumlahkontrakakselerasi = 0;
                        $nilaikomponenakselerasi = 0;
                        $jumlahkontrak53 = 0;
                        $jumlahkontrakselesaitw1 = 0;
                        $nilaikomponen53 = 0;
                        $nilai = 100;

                    }
                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'idbagian' => $idbagian,
                        'jumlahkontrak' => $jumlahkontrak,
                        'nilaikomponen' => $nilaikomponenketepatanwaktu,
                        'jumlahkontraktw1' => $jumlahkontraktw1,
                        'jumlahkontrakakselerasi' => $jumlahkontrakakselerasi,
                        'nilaikomponenakselerasi' => $nilaikomponenakselerasi,
                        'jumlahkontrak53' => $jumlahkontrak53,
                        'jumlahkontrak53akselerasi' => $jumlahkontrakselesaitw1,
                        'nilaikomponen53' => $nilaikomponen53,
                        'nilai' => $nilai
                    );

                    //delete angka lama
                    DB::table('ikpakontraktualbagian')
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikpakontraktualbagian')->insert($datainsert);

                }
            }
        }
    }

    public function aksiperhitunganikpakontraktualbiro($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databiro = DB::table('biro')
                ->where('status','=','on')
                ->whereIn('id',[677,688,605,728])
                ->get();
            foreach ($databiro as $db){
                $idbiro = $db->id;
                for($i=1; $i<=12;$i++){
                    $jumlahkontrak = DB::table('ikpadetilkontraktual')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','<=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->count();

                    if ($jumlahkontrak>0){
                        $jumlahkontraksmt1 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','<=',6)
                            ->where('kodesatker','=',$kodesatker)
                            ->count();

                        $nilaikomponendistribusi = ($jumlahkontraksmt1/$jumlahkontrak)*100;

                        //menghitung komponen akselerasi kontrak dini
                        $jumlahkontraktw1 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','<=',3)
                            ->where('kodesatker','=',$kodesatker)
                            ->count();
                        $jumlahkontrakdini = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('periode','=',1)
                            ->where('nilai_kontrak_dini','=',120)
                            ->where('kodesatker','=',$kodesatker)
                            ->count();
                        $jumlahkontraknondinitw1 = $jumlahkontraktw1 - $jumlahkontrakdini;
                        $nilaikomponenakselerasi = ((($jumlahkontrakdini*120)+($jumlahkontraknondinitw1*110))/$jumlahkontraktw1)*100;


                        //jumlah akselerasi 53
                        //jumlah kontrak akselerasi
                        $jumlahkontrak53 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where('kodesatker','=',$kodesatker)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->whereNotNull('tanggal_penyelesaian')
                            ->count();
                        $jumlahkontrakselesaitw1 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->whereMonth('tanggal_penyelesaian', '<=', 3)
                            ->count();
                        $nilaikontrakselesaitw1 = $jumlahkontrakselesaitw1*100;
                        $jumlahkontrakselesaitw2 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 3);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 6);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw2 = $jumlahkontrakselesaitw2*90;

                        $jumlahkontrakselesaitw3 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 6);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 9);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw3 = $jumlahkontrakselesaitw3 * 80;

                        $jumlahkontrakselesaitw4 = DB::table('ikpadetilkontraktual')
                            ->where('tahunanggaran','=',$tahunanggaran)
                            ->where('idbiro','=',$idbiro)
                            ->where('kodesatker','=',$kodesatker)
                            ->where('periode','<=',3)
                            ->where('jenisbelanja','=',"53")
                            ->where('periode','<=',$i)
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->where('nilai_kontrak','>',50000000);
                                    $q->where('nilai_kontrak','<=',200000000);
                                });
                            })
                            ->where(function ($query){
                                $query->where(function ($q){
                                    $q->whereMonth('tanggal_penyelesaian', '>', 9);
                                    $q->whereMonth('tanggal_penyelesaian', '<=', 12);
                                });
                            })
                            ->count();
                        $nilaikontrakselesaitw4 = $jumlahkontrakselesaitw4 * 70;
                        if ($jumlahkontrak53 > 0){
                            $nilaikomponen53 = ($nilaikontrakselesaitw1+$nilaikontrakselesaitw2+$nilaikontrakselesaitw3+$nilaikontrakselesaitw4)/$jumlahkontrak53;
                        }else{
                            $nilaikomponen53 = 100;
                        }

                        //hitung nilai ikpa
                        $nilai = ($nilaikomponendistribusi*0.2)+($nilaikomponenakselerasi*0.4)+($nilaikomponen53*0.4);
                        if($nilai > 100){
                            $nilai = 100.00;
                        }
                    }else{
                        $jumlahkontrak = 0;
                        $nilaikomponenketepatanwaktu = 0;
                        $jumlahkontrakdini = 0;
                        $nilaikomponendistribusi = 0;
                        $jumlahkontraktw1 = 0;
                        $jumlahkontrakakselerasi = 0;
                        $nilaikomponenakselerasi = 0;
                        $jumlahkontrak53 = 0;
                        $jumlahkontrakselesaitw1 = 0;
                        $nilaikomponen53 = 0;
                        $nilai = 100;

                    }
                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'jumlahkontrak' => $jumlahkontrak,
                        'nilaikomponen' => $nilaikomponendistribusi,
                        'jumlahkontraktw1' => $jumlahkontraktw1,
                        'jumlahkontrakakselerasi' => $jumlahkontrakdini,
                        'nilaikomponenakselerasi' => $nilaikomponenakselerasi,
                        'jumlahkontrak53' => $jumlahkontrak53,
                        'jumlahkontrak53akselerasi' => $jumlahkontrakselesaitw1,
                        'nilaikomponen53' => $nilaikomponen53,
                        'nilai' => $nilai
                    );

                    //delete angka lama
                    DB::table('ikpakontraktualbiro')
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->where('kodesatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikpakontraktualbiro')->insert($datainsert);

                }
            }
        }
    }
}
