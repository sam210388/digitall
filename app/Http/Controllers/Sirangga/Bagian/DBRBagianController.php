<?php

namespace App\Http\Controllers\Sirangga\Bagian;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Sirangga\Admin\DBRController;
use App\Libraries\KirimWhatsapp;
use App\Models\Sirangga\Admin\DetilDBRModel;
use App\Models\Sirangga\Bagian\DBRBagianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DBRBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dbrindukbagian(){
        $judul = "DBR Kewenangan Bagian";
        return view('Sirangga.Bagian.dbrbagian',[
            "judul"=>$judul,
        ]);
    }

    public function getdatadbrbagian()
    {
        $idbagian = Auth::user()->idbagian;
        $model = DBRBagianModel::with('statusdbrrelation')
            ->with('gedungrelation')
            ->with('penanggungjawabrelation')
            ->with('ruanganrelation')
            ->join('ruangan', 'ruangan.id', '=', 'dbrinduk.idruangan')
            ->join('statusdbr','statusdbr.id','=','dbrinduk.statusdbr')
            ->select('dbrinduk.*');
            if ($idbagian == "" or $idbagian == null or $idbagian == 0){
                $idbiro = Auth::user()->idbiro;
                $model = $model->where('ruangan.idbiro','=',$idbiro);
            }else{
                $model = $model->where('ruangan.idbagian','=',$idbagian);
            }

        return Datatables::of($model)
            ->addColumn('statusdbr', function (DBRBagianModel $dbr) {
                return $dbr->statusdbrrelation->uraianstatus;
            })
            ->addColumn('idgedung', function (DBRBagianModel $dbr) {
                return $dbr->gedungrelation->uraiangedung;
            })
            ->addColumn('idpenanggungjawab', function (DBRBagianModel $dbr) {
                return $dbr->penanggungjawabrelation->nama ?? 'Belum Ada Penanggungjawab';
            })
            ->addColumn('uraianruangan', function (DBRBagianModel $dbr) {
                return $dbr->ruanganrelation->uraianruangan ?? 'Belum Ada Ruangan';
            })
            ->addColumn('useredit', function (DBRBagianModel $dbr) {
                return $dbr->userrelation->name ?? 'User Belum Ditetapkan';
            })
            ->addColumn('dokumendbr',function ($row){
                if (Storage::disk('public')->missing('/pengesahandbrfinal/PengesahanDBRRuangan'.$row->iddbr."VersiKe".$row->versike.'.pdf')){
                    $linkpengesahan = "File Tidak Ada";
                }else{
                    $linkpengesahan = '<a href="'.env('APP_URL')."/".asset('storage')."/pengesahandbrfinal/PengesahanDBRRuangan".$row->iddbr."VersiKe".$row->versike.'.pdf" >Download Pengesahan</a>';
                }

                //$datalokasidbrfinal = getenv('APP_URL')."/".asset('storage')."/dbrfinaldigitall/DBRRuangan".$row->iddbr.".pdf";
                if (Storage::disk('public')->missing('/dbrfinaldigitall/DBRRuangan'.$row->iddbr."VersiKe".$row->versike.'.pdf')){
                    $linkdokumendbr = "File Tidak Ada";
                }else{
                    $linkdokumendbr = '<a href="'.env('APP_URL')."/".asset('storage')."/dbrfinaldigitall/DBRRuangan".$row->iddbr."VersiKe".$row->versike.'.pdf" >Download DBR</a>';
                }
                return "Link Pengesahan: ".$linkpengesahan." Link DBR: ".$linkdokumendbr;
            })
            ->addColumn('action', function(DBRBagianModel $dbr){
                //$jumlahdetil = DB::table('detildbr')->where('iddbr','=',$row->iddbr)->count();
                if($dbr->statusdbrrelation->uraianstatus == "Diajukan Ke Unit"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$dbr->iddbr.'" data-original-title="setujudbr" class="edit btn btn-primary btn-sm setujuidbr">Setuju DBR</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' .$dbr->iddbr.'" data-original-title="tolakdbr" class="edit btn btn-danger btn-sm tolakdbr">Tolak DBR</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' .$dbr->iddbr.'" data-original-title="lihatdbr" class="edit btn btn-info btn-sm lihatdbr">Lihat DBR</a>';
                }else if ($dbr->statusdbrrelation->uraianstatus == "Final"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$dbr->iddbr.'" data-original-title="laporpenambahan" class="edit btn btn-primary btn-sm laporpenambahan">Lapor Perubahan</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' .$dbr->iddbr.'" data-original-title="lihatdbr" class="edit btn btn-info btn-sm lihatdbr">Lihat DBR</a>';
                }else{
                    $btn = "";
                }
                return $btn;
            })
            ->rawColumns(['action','dokumendbr'])
            ->toJson();
    }

    public function setujuidbr($iddbr){
        $penanggungjawab = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('idpenanggungjawab');
        $namapenanggungjawab = DB::table('pegawai')->where('id','=',$penanggungjawab)->value('nama');
        $batasakhir = Carbon::now();
        $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');

        $adabarang = DB::table('detildbr')
            ->where('iddbr','=',$iddbr)
            ->where('statusbarang','=',"Tidak Ada")
            ->count();
        if ($adabarang > 0){
            return response()->json(['status'=>'konfirmbarang']);
        }else{
            $dataupdate = array(
                'statusdbr' => 3,
                'usersetujutolakdbr' => Auth::id(),
                'terakhiredit' => now(),
                'tanggalpersetujuandbr' => now(),
                'dokumendbr' => "DBRRuangan".$iddbr.".pdf"
            );
            DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

            //cetak dan simpan DBR
            $cetakdbr = new DBRController();
            $cetakdbr = $cetakdbr->cetakdbr($iddbr);

            //kirim notif
            $notif = new KirimWhatsapp();
            $notif = $notif->persetujuandbr($namapenanggungjawab, $iddbr,$uraianbatasakhir);

            return response()->json(['status'=>'berhasil']);
        }
    }

    public function penolakandbr(Request $request, $iddbr){
        $penanggungjawab = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('idpenanggungjawab');
        $namapenanggungjawab = DB::table('pegawai')->where('id','=',$penanggungjawab)->value('nama');
        $batasakhir = Carbon::now();
        $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');
        $alasanpenolakan = $request->get('alasanpenolakan');
        $dataupdate = array(
            'statusdbr' => 1,
            'alasanpenolakan' => $alasanpenolakan,
            'usersetujutolakdbr' => Auth::id(),
            'terakhiredit' => now(),
            'tanggalpersetujuandbr' => now()
        );
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

        //kirim notif
        $notif = new KirimWhatsapp();
        $notif = $notif->penolakandbr($namapenanggungjawab, $iddbr,$uraianbatasakhir);

        return response()->json(['status'=>'berhasil']);
    }

    public function laporperubahan(Request $request, $iddbr){
        $penanggungjawab = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('idpenanggungjawab');
        $namapenanggungjawab = DB::table('pegawai')->where('id','=',$penanggungjawab)->value('nama');
        $batasakhir = Carbon::now();
        $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');
        $dataupdate = array(
            'statusdbr' => 1,
            'userperubahdbr' => Auth::id(),
            'tanggalperubahandbr' => now(),
        );
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

        //kirim notif
        $notif = new KirimWhatsapp();
        $notif = $notif->pengajuanperubahan($namapenanggungjawab, $iddbr,$uraianbatasakhir);

        return response()->json(['status'=>'berhasil']);
    }

    //TODO
    //UNTUK PENGAJUAN PERUBAHAN DBR JIKA UNIT MENERIMA BARANG DARI ULP
    //PERUBAHAN INI MEMBUAT DBR BERUBAH STATUS MENJADI PENGAJUAN PERUBAHAN, NAMUN PERSETUJUAN TETEP BUTUH KONFIRM DARI BMN
    //PROSES AKAN MEMBUAT ENTRI PADA TABEL BARU

    public function lihatdbrbagian($iddbr){
        $judul = "Data Barang DBR";
        return view('Sirangga.Bagian.lihatdbrbagian',[
            "judul"=>$judul,
            "iddbr" => $iddbr
        ]);
    }

    public function getdatadetildbr($iddbr){
        $model = DetilDBRModel::where('iddbr','=',$iddbr);
        $versike = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('versike');
        $statusdbr = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('statusdbr');
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('action', function($row) use ($versike, $statusdbr){
                if ($row->statusbarang == "Ada" and $versike == 1 ){
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Konfirmasi" class="btn btn-danger btn-sm konfirmasitidakada">Tidak Ada</a>';
                    //$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Pemeliharaan" class="btn btn-info btn-sm pemeliharaan">Pemeliharaan</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Pengembalian" class="btn btn-danger btn-sm pengembalian">Pengembalian</a>';
                }else if ($row->statusbarang == "Ada" and $versike > 1 ) {
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->iddetil . '" data-original-title="Hilang" class="btn btn-danger btn-sm konfirmasihilang">Hilang</a>';
                    //$btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Pemeliharaan" class="btn btn-info btn-sm pemeliharaan">Pemeliharaan</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Pengembalian" class="btn btn-danger btn-sm pengembalian">Pengembalian</a>';
                } else if($row->statusbarang == "Tidak Ada" and $row->statusdbr == 1){
                    $btn = "";
                }else if($row->statusbarang == "Tidak Ada" and $row->statusdbr == 2){
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Delete" class="btn btn-danger btn-sm konfirmasitidakada">Tidak Ada</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Konfirmasi" class="btn btn-success btn-sm konfirmasiada">Ada</a>';
                }
                else if($row->statusbarang == "Hilang" || $row->statusbarang == "Pengembalian"){
                    $btn = "";
                }
                else{
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Delete" class="btn btn-danger btn-sm konfirmasitidakada">Tidak Ada</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Konfirmasi" class="btn btn-success btn-sm konfirmasiada">Ada</a>';
                }
                return $btn;
            })
            ->rawColumns(['dokumendbr','action'])
            ->toJson();
    }

    public function konfirmasibarangada(Request $request){
        $iddetil = $request->get('iddetil');

        //ubah status dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->update([
            'statusbarang' => "Ada",
            'terakhirperiksa' => now(),
            'diperiksaoleh' => Auth::id()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }

    public function konfirmasibarangtidakada(Request $request){
        $iddetil = $request->get('iddetil');

        //ubah status dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->update([
            'statusbarang' => "Tidak Ada",
            'terakhirperiksa' => now(),
            'diperiksaoleh' => Auth::id()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }

    public function konfirmasibaranghilang(Request $request){
        $iddetil = $request->get('iddetil');

        //ubah status dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->update([
            'statusbarang' => "Hilang",
            'terakhirperiksa' => now(),
            'diperiksaoleh' => Auth::id()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }

    public function konfirmasibarangpemeliharaan(Request $request){
        $iddetil = $request->get('iddetil');

        //ubah status dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->update([
            'statusbarang' => "Pemeliharaan",
            'terakhirperiksa' => now(),
            'diperiksaoleh' => Auth::id()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }

    public function konfirmasibarangpengembalian(Request $request){
        $iddetil = $request->get('iddetil');

        //ubah status dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->update([
            'statusbarang' => "Pengembalian",
            'terakhirperiksa' => now(),
            'diperiksaoleh' => Auth::id()
        ]);
        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
    }
}
