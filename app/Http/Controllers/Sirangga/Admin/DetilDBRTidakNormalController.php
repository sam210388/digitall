<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Exports\ExportDBRInduk;
use App\Exports\ExportDetilDBR;
use App\Exports\ExportDetilDBRTidakNormal;
use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\DetilDBRModel;
use App\Models\Sirangga\Admin\DetilDBRTidakNormalModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DetilDBRTidakNormalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function detildbr(){
        $totalbarangterdata = DB::table('detildbrtidaknormal')->count();
        $judul = "Data Detil DBR Tidak Normal";
        return view('Sirangga.Admin.detildbrtidaknormal',[
            "judul"=>$judul,
            "barangterdata" => $totalbarangterdata
        ]);
    }

    function exportdetildbr($statusbarang){
        return Excel::download(new ExportDetilDBRTidakNormal($statusbarang),'DetilDBRTidakNormal.xlsx');
    }

    public function getDataDetilBDRTidakNormal()
    {
        $model = DetilDBRTidakNormalModel::with('dbrindukrelation')
            ->select('detildbrtidaknormal.*');
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('action', function($row){
                if ($row->statusbarang == "Hilang" || $row->statusbarang == "Pengembalian"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="KonfirmPengembalian" class="edit btn btn-info btn-sm konfirmhilangkembali">Konfirm Hilang/Kembali</a>';
                }else{
                    $btn = "";
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function konfirmhilangkembali(Request $request){
        $iddetil = $request->get('iddetil');
        $iddbr = DB::table('detildbr')->where('iddetil','=',$iddetil)->value('iddbr');
        $idbarang = DB::table('detildbr')->where('iddetil','=',$iddetil)->value('idbarang');

        //insertkan datanya di Barang terkonfirmasi hilang atau kembali
        $detildbr = DB::table('detildbr')->where('iddetil','=',$iddetil)->get();
        foreach ($detildbr as $item) {
            $iddbr = $item->iddbr;
            $idbarang = $item->idbarang;
            $kd_lokasi = $item->kd_lokasi;
            $kd_brg = $item->kd_brg;
            $no_aset = $item->no_aset;
            $uraianbarang = $item->uraianbarang;
            $tahunperolehan = $item->tahunperolehan;
            $merek = $item->merek;
            $statusbarcode = $item->statusbarcode;
            $iduser = $item->iduser;
            $waktumasukdbr = $item->waktumasukdbr;
            $statusbarang = $item->statusbarang;
            $terakhirperiksa = $item->terakhirperiksa;
            $diperiksaoleh = $item->diperiksaoleh;

            $datainsert = array(
                'iddetil' => $iddetil,
                'iddbr' => $iddbr,
                'idbarang' => $idbarang,
                'kd_lokasi' => $kd_lokasi,
                'kd_brg' => $kd_brg,
                'no_aset' => $no_aset,
                'uraianbarang' => $uraianbarang,
                'tahunperolehan' => $tahunperolehan,
                'merek' => $merek,
                'statusbarcode' => $statusbarcode,
                'iduser' => $iduser,
                'waktumasukdbr' => $waktumasukdbr,
                'waktukeluardbr' => now(),
                'statusbarang' => $statusbarang,
                'terakhirperiksa' => $terakhirperiksa,
                'diperiksaoleh' => $diperiksaoleh,
                'terkonfirmasioleh' => Auth::id(),
                'terkonfirmasipada' => now()
            );
            DB::table('konfirmhilangkembali')->insert($datainsert);

        }

        //perubahan final DBR
        $dbrcontroller = new DBRController();
        $perubahanfinal = $dbrcontroller->perubahanfinal($iddbr);

        //hapus data detil
        DB::table('detildbr')->where('iddetil','=',$iddetil)->delete();

        //update status barang menjadi belum dbr
        DB::table('barang')->where('id','=',$idbarang)->update([
            'statusdbr' => 1
        ]);

        //ubah DBR menjadi berstatus draft
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update([
            'statusdbr' => 1,
            'useredit' => Auth::id(),
            'terakhiredit' => now()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }
}
