<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Exports\ExportIkpaPenyelesaianBagian;
use App\Exports\ExportIkpaPenyelesaianBiro;
use App\Http\Controllers\Controller;
use App\Jobs\HitungIkpaPenyelesaianBagian;
use App\Jobs\HitungIkpaPenyelesaianBiro;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihanBiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IKPAPenyelesaianTagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'IKPA Penyelesaian Tagihan';
        $databagian = DB::table('bagian')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpapenyelesaian',[
            "judul"=>$judul,
            "databagian" => $databagian
        ]);
    }

    public function indexbiro(){
        $judul = 'IKPA Penyelesaian Tagihan';
        $databiro = DB::table('biro')->where('status','=','on')->get();
        return view('IKPA.Admin.ikpapenyelesaianbiro',[
            "judul"=>$judul,
            "databiro" => $databiro
        ]);
    }

    public function hitungikpapenyelesaianbagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaPenyelesaianBagian($tahunanggaran));
        return redirect()->to('ikpapenyelesaiantagihan')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    public function hitungikpapenyelesaianbiro(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new HitungIkpaPenyelesaianBiro($tahunanggaran));
        return redirect()->to('ikpapenyelesaiantagihanbiro')->with(['status' => 'Perhitungan IKPA Berhasil Dilakukan Diserver, Harap Tunggu Beberapa Saat']);
    }

    function exportikpapenyelesaianbiro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaPenyelesaianBiro($tahunanggaran),'IKPAPenyelesaianBiro.xlsx');
    }

    function exportikpapenyelesaianbagian(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportIkpaPenyelesaianBagian($tahunanggaran),'IKPAPenyelesaianBagian.xlsx');
    }

    public function getdataikpapenyelesaian(Request $request,$idbagian=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyelesaianTagihan::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpapenyelesaiantagihan.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != "") {
                $data->where('idbagian', '=', $idbagian);
            }
            return Datatables::of($data)
                ->addColumn('bagian', function (IkpaPenyelesaianTagihan $id) {
                    return $id->idbagian?$id->bagianrelation->uraianbagian:"";
                })
                ->addColumn('biro', function (IkpaPenyelesaianTagihan $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['bagian','biro'])
                ->make(true);
        }
    }

    public function getdataikpapenyelesaianbiro(Request $request,$idbiro=null){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyelesaianTagihanBiro::with('birorelation')
                ->select(['ikpapenyelesaiantagihanbiro.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbiro')
                ->orderBy('periode','asc');
            if ($idbiro != "") {
                $data->where('idbiro', '=', $idbiro);
            }
            return Datatables::of($data)
                ->addColumn('biro', function (IkpaPenyelesaianTagihanBiro $id) {
                    return $id->idbiro? $id->birorelation->uraianbiro:"";
                })
                ->rawColumns(['biro'])
                ->make(true);
        }
    }

    public function aksiperhitunganpenyelesaianbagian($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databagian = DB::table('bagian')->where('status','=','on')->get();
            foreach ($databagian as $db){
                $idbagian = $db->id;
                $idbiro = $db->idbiro;
                for($i=1; $i<=12;$i++){
                    $tepatwaktuakumulatif = DB::table('detilpenyelesaiantagihanbagian')
                        ->select(['detilpenyelesaiantagihan.*'])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('status','=',"TEPAT")
                        ->where('periode','<=',$i)
                        ->count();

                    $terlambat = DB::table('detilpenyelesaiantagihanbagian')
                        ->select(['detilpenyelesaiantagihan.*'])
                        ->where('idbagian','=',$idbagian)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('status','=',"TERLAMBAT")
                        ->where('periode','<=',$i)
                        ->count();
                    $total = $tepatwaktuakumulatif + $terlambat;
                    if ($total > 0){
                        $persen = ($tepatwaktuakumulatif/$total)*100;
                    }else{
                        $persen = 100;
                    }
                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kdsatker' => $kodesatker,
                        'periode' => $i,
                        'idbagian' => $idbagian,
                        'idbiro' => $idbiro,
                        'tepatwaktuakumulatif' => $tepatwaktuakumulatif,
                        'terlambatakumulatif' => $terlambat,
                        'totalakumulatif' => $total,
                        'persen' => $persen
                    );
                    //delete angka lama
                    DB::table('ikpapenyelesaiantagihan')
                        ->where('idbagian','=',$idbagian)
                        ->where('periode','=',$i)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikpapenyelesaiantagihan')->insert($datainsert);

                }
            }
        }
    }

    public function aksiperhitunganpenyelesaianbiro($tahunanggaran){
        //ambil data satker
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $item){
            $kodesatker = $item;
            //ambil data bagian
            $databiro = DB::table('biro')->where('status','=','on')->get();
            foreach ($databiro as $db){
                $idbiro = $db->id;
                for($i=1; $i<=12;$i++){
                    $tepatwaktuakumulatif = DB::table('detilpenyelesaiantagihanbagian')
                        ->select(['detilpenyelesaiantagihan.*'])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('status','=',"TEPAT")
                        ->where('periode','<=',$i)
                        ->count();

                    $terlambat = DB::table('detilpenyelesaiantagihanbagian')
                        ->select(['detilpenyelesaiantagihan.*'])
                        ->where('idbiro','=',$idbiro)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('status','=',"TERLAMBAT")
                        ->where('periode','<=',$i)
                        ->count();
                    $total = $tepatwaktuakumulatif + $terlambat;
                    if ($total > 0){
                        $persen = ($tepatwaktuakumulatif/$total)*100;
                    }else{
                        $persen = 100;
                    }
                    $datainsert = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kdsatker' => $kodesatker,
                        'periode' => $i,
                        'idbiro' => $idbiro,
                        'tepatwaktuakumulatif' => $tepatwaktuakumulatif,
                        'terlambatakumulatif' => $terlambat,
                        'totalakumulatif' => $total,
                        'persen' => $persen
                    );
                    //delete angka lama
                    DB::table('ikpapenyelesaiantagihanbiro')
                        ->where('idbiro','=',$idbiro)
                        ->where('periode','=',$i)
                        ->where('kdsatker','=',$kodesatker)
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->delete();

                    DB::table('ikpapenyelesaiantagihanbiro')->insert($datainsert);

                }
            }
        }
    }
}
