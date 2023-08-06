<?php

namespace App\Http\Controllers\Sirangga\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\DetilDBRModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $model = DB::table('dbrinduk as a')
            ->select(['a.iddbr as iddbr','a.idpenanggungjawab as idpenanggungjawab','b.uraiangedung as uraiangedung',
                'a.idruangan as idruangan','c.uraianruangan as uraianruangan','d.uraianstatus as statusdbr','e.name as useredit',
                'a.terakhiredit as terakhiredit','a.versike as versike','a.dokumendbr as dokumendbr'])
            ->leftJoin('gedung as b','a.idgedung','=','b.id')
            ->leftJoin('ruangan as c','a.idruangan','=','c.id')
            ->leftJoin('statusdbr as d','a.statusdbr','=','d.id')
            ->leftJoin('users as e','a.useredit','=','e.id')
            ->leftJoin('pegawai as f','a.idpenanggungjawab','=','f.id')
            ->where('c.idbagian','=',$idbagian)
            ->get();
        return Datatables::of($model)
            ->addColumn('statusdbr', function ($dbr) {
                return $dbr->statusdbr;
            })
            ->addColumn('idgedung', function ($dbr) {
                return $dbr->uraiangedung;
            })
            ->addColumn('idpenanggungjawab', function ($dbr) {
                return $dbr->nama ?? 'Belum Ada Penanggungjawab';
            })
            ->addColumn('uraianruangan', function ($dbr) {
                return $dbr->uraianruangan ?? 'Belum Ada Ruangan';
            })
            ->addColumn('useredit', function ($dbr) {
                return $dbr->useredit ?? 'User Belum Ditetapkan';
            })
            ->addColumn('dokumendbr',function ($row){
                if ($row->dokumendbr != null or $row->dokumendbr != ""){
                    $linkdokumendbr = '<a href="'.env('APP_URL')."/".asset('storage')."/dbrfinal/".$row->dokumendbr.'" >Download DBR</a>';
                }else{
                    $linkdokumendbr = "";
                }
                return $linkdokumendbr;
            })
            ->addColumn('action', function($row){
                $jumlahdetil = DB::table('detildbr')->where('iddbr','=',$row->iddbr)->count();
                if($row->statusdbr == "Diajukan Ke Unit"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="setujudbr" class="edit btn btn-primary btn-sm setujuidbr">Setuju DBR</a>';
                    return $btn;
                }else if ($row->statusdbr == "Final"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="laporpenambahan" class="edit btn btn-primary btn-sm laporpenambahan">Lapor Perubahan</a>';
                    return $btn;
                }else{
                    return $btn = "";
                }
            })
            ->rawColumns(['action','dokumendbr'])
            ->toJson();
    }


    public function setujuidbr($iddbr){
        $adabarang = DB::table('detildbr')
            ->where('iddbr','=',$iddbr)
            ->where('statusbarang','=',"Tidak Ada")
            ->count();
        if ($adabarang > 0){
            return response()->json(['status'=>'konfirmbarang']);
        }else{
            $dataupdate = array(
                'statusdbr' => 4,
                'useredit' => Auth::id(),
                'terakhiredit' => now(),
                'tanggalpersetujuandbr' => now()
            );
            DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }
    }

    //TODO
    //UNTUK PENGAJUAN PERUBAHAN DBR JIKA UNIT MENERIMA BARANG DARI ULP
    //PERUBAHAN INI MEMBUAT DBR BERUBAH STATUS MENJADI PENGAJUAN PERUBAHAN, NAMUN PERSETUJUAN TETEP BUTUH KONFIRM DARI BMN
    //PROSES AKAN MEMBUAT ENTRI PADA TABEL BARU

    function ajukanperubahan(Request $request){
        $iddbr = $request->get('iddbr');
        $jumlahbarangdilaporkan = $request->get('jumlahbarangdilaporkan');
        $deskripsibarangdilaporkan = $request->get('deskripsibarangdilaporkan');

        //AMBIL DATA DBR INDUK
        $datadbr = DB::table('dbrinduk as a')
            ->select(['a.idruangan as idruangan','b.idbagian as idbagian'])
            ->leftJoin('ruangan as b','a.idruangan','=','b.id')
            ->get();
        foreach ($datadbr as $d){
            $idruangan = $d->idruangan;
            $idbagian = $d->idbagian;

            //insert data di pengajuan perubahan final
            DB::table('pengajuanperubahanfinal')->UpdateOrInsert(['iddbr' => $iddbr],[
                'iddbr' => $iddbr,
                'idruangan' => $idruangan,
                'idbagian' => $idbagian,
                'diajukanoleh' => Auth::id(),
                'tanggalpengajuan' => now(),
                'tanggalditindaklanjuti' => null,
                'ditindaklanjutioleh' => null,
                'jumlahbarangdilaporkan' => $jumlahbarangdilaporkan,
                'deskripsibarangdilaporkan' => $deskripsibarangdilaporkan,
                'statuspengajuan' => 1
            ]);
        }
        return response()->json(['status'=>'berhasil']);

    }




    public function lihatdbr($iddbr){
        $judul = "Data Barang DBR";
        return view('Sirangga.Bagian.lihatdbrbagian',[
            "judul"=>$judul,
            "iddbr" => $iddbr
        ]);
    }

    public function getdatadetildbr($iddbr){
        $model = DetilDBRModel::where('iddbr','=',$iddbr);

        return Datatables::eloquent($model)
            ->addColumn('action', function($row){
                if ($row->statusbarang == "Ada"){
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Delete" class="btn btn-danger btn-sm deletebarang">Delete</a>';
                }else{
                    $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Delete" class="btn btn-danger btn-sm deletebarang">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddetil.'" data-original-title="Konfirmasi" class="btn btn-success btn-sm konfirmasibarang">Konfirm</a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
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

}
