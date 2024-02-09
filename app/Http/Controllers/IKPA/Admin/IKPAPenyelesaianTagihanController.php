<?php

namespace App\Http\Controllers\IKPA\Admin;

use App\Http\Controllers\Controller;
use App\Models\IKPA\Admin\IKPADeviasiModel;
use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getdataikpapenyelesaian(Request $request,$idbagian){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data =IkpaPenyelesaianTagihan::with('bagianrelation')
                ->with('birorelation')
                ->select(['ikpapenyelesaiantagihan.*'])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->orderBy('kdsatker','asc')
                ->orderBy('idbagian')
                ->orderBy('periode','asc');
            if ($idbagian != 0) {
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
                    $persen = ($tepatwaktuakumulatif/$total)*100;

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
}
