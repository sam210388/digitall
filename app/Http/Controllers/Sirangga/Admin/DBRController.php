<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Exports\DataBartenderExport;
use App\Http\Controllers\Controller;
use App\Libraries\KirimWhatsapp;
use App\Models\Sirangga\Admin\BarangModel;
use App\Models\Sirangga\Admin\DBRIndukModel;
use App\Models\Sirangga\Admin\DetilDBRModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Twilio\Rest\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DBRController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dbrinduk(){
        $dbrtotal = DB::table('dbrinduk')->count();
        $dbrdraft = DB::table('dbrinduk')->where('statusdbr','=',1)->count();
        $dbrunit = DB::table('dbrinduk')->where('statusdbr','=',2)->count();
        $dbrfinal = DB::table('dbrinduk')->where('statusdbr','=',3)->count();
        $pegawai = DB::table('pegawai')->get();
        $judul = "Lihat DBR";
        return view('Sirangga.Admin.dbr',[
            "judul"=>$judul,
            "dbrtotal" => $dbrtotal,
            "dbrdraft" => $dbrdraft,
            "dbrunit" => $dbrunit,
            "dbrfinal" => $dbrfinal,
            "datapegawai" => $pegawai
        ]);
    }

    public function getDataBDR()
    {
        $model = DBRIndukModel::with('statusdbrrelation')
            ->with('gedungrelation')
            ->with('penanggungjawabrelation')
            ->with('ruanganrelation')
            ->select('dbrinduk.*');
        return Datatables::eloquent($model)
            ->addColumn('statusdbr', function (DBRIndukModel $dbr) {
                return $dbr->statusdbrrelation->uraianstatus;
            })
            ->addColumn('idgedung', function (DBRIndukModel $dbr) {
                return $dbr->gedungrelation->uraiangedung;
            })
            ->addColumn('idpenanggungjawab', function (DBRIndukModel $dbr) {
                return $dbr->penanggungjawabrelation->nama ?? 'Belum Ada Penanggungjawab';
            })
            ->addColumn('uraianruangan', function (DBRIndukModel $dbr) {
                return $dbr->ruanganrelation->uraianruangan ?? 'Belum Ada Ruangan';
            })
            ->addColumn('useredit', function (DBRIndukModel $dbr) {
                return $dbr->userrelation->name ?? 'User Belum Ditetapkan';
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
                if ($row->statusdbrrelation->id == 1 && $row->versike == 1){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Edit" class="edit btn btn-info btn-sm editdbr">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedbr">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Tambah Barang" class="btn btn-primary btn-sm tambahbarang">Tambah Barang
                                <span class="badge badge-danger navbar-badge">'.$jumlahdetil.'</span></a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Kirim" class="btn btn-success btn-sm kirimkeunit">Kirim</a>';
                    return $btn;
                }elseif ($row->statusdbrrelation->id == 1 && $row->versike >1 ){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Edit" class="edit btn btn-info btn-sm editdbr">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Tambah Barang" class="btn btn-secondary btn-sm tambahbarang">Tambah Barang
                                <span class="badge badge-danger navbar-badge">'.$jumlahdetil.'</span></a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Kirim" class="btn btn-success btn-sm kirimkeunit">Kirim</a>';
                    return $btn;
                }elseif($row->statusdbrrelation->id == 2){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="ingatkanunit" class="edit btn btn-primary btn-sm ingatkanunit">Ingatkan Unit</a>';
                    return $btn;
                }else{
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="perubahanfinal" class="edit btn btn-primary btn-sm perubahanfinal">Perubahan Final</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="CekFisik" class="btn btn-secondary btn-sm cekfisik">CekFisik</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="CetakDBR" class="btn btn-success btn-sm cetakdbr">Cetak DBR</a>';
                    return $btn;
                }
            })
            ->rawColumns(['action','dokumendbr'])
            ->toJson();
    }

    public function cetakdbr($iddbr){
        $datareferensidbr = DB::table('dbrinduk as a')
            ->select(['a.iddbr as iddbr','a.idpenanggungjawab as idpenanggungjawab','g.nama as penanggungjawab','g.nip as nip',
                'a.tanggalpengajuanunit as tanggalpengajuanunit','a.tanggalpersetujuandbr as tanggalpersetujuandbr',
                'b.uraianarea as area','c.uraiansubarea as subarea',
                'd.uraiangedung as gedung','e.uraianlantai as lantai','f.uraianruangan as ruangan'])
            ->leftJoin('ruangan as f','a.idruangan','=','f.id')
            ->leftJoin('area as b','b.id','=','f.idarea')
            ->leftJoin('subarea as c','c.id','=','f.idsubarea')
            ->leftJoin('gedung as d','d.id','=','f.idgedung')
            ->leftJoin('lantai as e','e.id','=','f.idlantai')
            ->leftJoin('pegawai as g','g.id','=','a.idpenanggungjawab')
            ->where('iddbr','=',$iddbr)
            ->get();

        //membuat qrcode
        $penanggungjawab ="";
        $nip = "";
        $tanggalpersetujuandbr = "";
        $tanggalpengajuanunit = "";
        $waktucetak = now();
        foreach ($datareferensidbr as $data){
            $penanggungjawab = $data->penanggungjawab;
            $tanggalpengajuanunit = $data->tanggalpengajuanunit;
            $tanggalpersetujuandbr = $data->tanggalpersetujuandbr;
            $nip = $data->nip;
            $dataqrunit = "Penanggungjawab: ".$penanggungjawab." Disetujui Pada: ".$tanggalpersetujuandbr;
            QrCode::generate($dataqrunit,asset('storage/qrunit/DBR'.$iddbr.'.svg'));
        }

        $datalokasidbrfinal = getenv('APP_URL')."/".asset('storage')."/dbrfinaldigitall/DBRRuangan ".$iddbr.".pdf";
        QrCode::generate($datalokasidbrfinal,asset('storage/qrdbrfinal/DBR'.$iddbr.'.svg'));

        //penandatangan
        $namapenandatangan = "";
        $nippenandatangan = "";
        $jabatanpenandatangan = "";

        $datapenandatangan = DB::table('penandatangan')
            ->where('jenisdokumen','=','DBR')
            ->where('status','=','Aktif')
            ->get();
        foreach ($datapenandatangan as $dp){
            $namapenandatangan = $dp->namalengkap;
            $nippenandatangan = $dp->nip;
            $jabatanpenandatangan = $dp->jabatan;
        }
        $dataqrbmn = "Disiapkan Oleh: ".$namapenandatangan." Diajukan ke Unit Pada: ".$tanggalpengajuanunit;
        QrCode::generate($dataqrbmn,asset('storage/qrbmn/DBR'.$iddbr.'.svg'));

        //data detildbr
        $datadetildbr = DB::table('detildbr')->where('iddbr','=',$iddbr);
        $listbarang = $datadetildbr->get();
        $jumlahbarang = $datadetildbr->count();
        $pdf = Pdf::loadView('laporan.sirangga.dbrruangan',[
            'datareferensidbr' => $datareferensidbr,
            'datadetildbr' => $listbarang,
            'jumlahbarang' => $jumlahbarang,
            'iddbr' => $iddbr,
            'penanggungjawab' => $penanggungjawab,
            'nip' => $nip,
            'waktucetak' => $waktucetak,
            'namapenandatangan' => $namapenandatangan,
            'nippenandatangan' => $nippenandatangan,
            'jabatanpenandatangan' => $jabatanpenandatangan
        ]);

        Storage::put('public/dbrfinaldigitall/DBRRuangan '.$iddbr.'.pdf', $pdf->output());
        return $pdf->stream('DBRRuangan '.$iddbr.'.pdf');
    }


    public function updatepenanggungjawabdbr(Request $request, $iddbr){
        $dataupdate = array(
            'idpenanggungjawab' => $request->get('idpenanggungjawab'),
            'useredit' => Auth::id(),
            'terakhiredit' => now()
        );
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);
        return response()->json(['status'=>'berhasil']);
    }

    public function editdbr($iddbr){
        $data = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->get();
        return response()->json($data);
    }

    public function deletedbr(Request $request, $iddbr){
        //cek apakah ada barang didalamnya
        $adabarang = DB::table('detildbr')->where('iddbr','=',$iddbr)->count();
        if ($adabarang > 0){
            return response()->json(['status'=>'adabarang']);
        }else{
            DB::table('dbrinduk')->where('iddbr','=',$iddbr)->delete();
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function testanggal(){
        /*
        $datadbr = DB::table('dbrinduk')->where('iddbr','=',1)->get();
        $tanggalpengajuanunit = "";
        $batasakhir = "";
        foreach ($datadbr as $item) {
            $tanggalpengajuanunit = $item->tanggalpengajuanunit;
            $tanggalpengajuanunit = Carbon::parse($tanggalpengajuanunit);
            $batasakhir = $item->batasakhir;
            if ($batasakhir == Null){
                $batasakhir = Carbon::now();
            }else{
                $batasakhir = $batasakhir;
            }

        }
        echo $tanggalpengajuanunit->isoFormat('D MMMM Y');
        */
        $datareferensidbr = DB::table('dbrinduk as a')
            ->select(['a.iddbr as iddbr','a.idpenanggungjawab as idpenanggungjawab','g.nama as penanggungjawab','g.nip as nip',
                'a.tanggalpengajuanunit as tanggalpengajuanunit','a.tanggalpersetujuandbr as tanggalpersetujuandbr',
                'b.uraianarea as area','c.uraiansubarea as subarea',
                'd.uraiangedung as gedung','e.uraianlantai as lantai','f.uraianruangan as ruangan'])
            ->leftJoin('ruangan as f','a.idruangan','=','f.id')
            ->leftJoin('area as b','b.id','=','f.idarea')
            ->leftJoin('subarea as c','c.id','=','f.idsubarea')
            ->leftJoin('gedung as d','d.id','=','f.idgedung')
            ->leftJoin('lantai as e','e.id','=','f.idlantai')
            ->leftJoin('pegawai as g','g.id','=','a.idpenanggungjawab')
            ->where('iddbr','=',1)
            ->get();

        //membuat qrcode
        $penanggungjawab ="";
        $nip = "";
        $tanggalpersetujuandbr = "";
        $tanggalpengajuanunit = "";
        $waktucetak = now();
        foreach ($datareferensidbr as $data){
            $penanggungjawab = $data->penanggungjawab;
            $tanggalpengajuanunit = $data->tanggalpengajuanunit;
            $tanggalpersetujuandbr = $data->tanggalpersetujuandbr;
            $nip = $data->nip;
        }

        $dataqrunit = "Penanggungjawab: ".$penanggungjawab." Disetujui Pada: ".$tanggalpersetujuandbr;
        echo $dataqrunit;

    }

    public function kirimdbrkeunit($iddbr){
        $adabarang = DB::table('detildbr')->where('iddbr','=',$iddbr)->count();
        $penanggungjawab = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('idpenanggungjawab');
        $phonepenanggungjawab = DB::table('pegawai')->where('id','=',$penanggungjawab)->value('phone');
        $namapenanggungjawab = DB::table('pegawai')->where('id','=',$penanggungjawab)->value('nama');
        $tanggalpengajuanunit = Carbon::now();

        $batasakhir = Carbon::now()->addDay(10);
        $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');

        if ($adabarang == 0){
            return response()->json(['status'=>'adabarang']);
        }else if ($penanggungjawab == NULL or $penanggungjawab == 0){
            return response()->json(['status'=>'belumpenanggungjawab']);
        }
        else{
            $dataupdate = array(
                'statusdbr' => 2,
                'useredit' => Auth::id(),
                'terakhiredit' => now(),
                'tanggalpengajuanunit' => $tanggalpengajuanunit,
                'batasakhir' => $batasakhir
            );
            DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

            //kirim notif
            $notif = new KirimWhatsapp();
            $notif = $notif->kirimdbr($phonepenanggungjawab, $namapenanggungjawab, $iddbr,$uraianbatasakhir);

            if($notif == "Sukses"){
                return response()->json(['status'=>'berhasil']);
                //echo json_encode($notif);
            }else{
                return response()->json(['status'=>'gagal']);
            }

        }
    }

    public function aksikirimperingatankeunit(){
        $dbrstatusdua = DB::table('dbrinduk')
            ->where('statusdbr','=',2)
            ->get();
        $tanggalsekarang = Carbon::now();
        foreach ($dbrstatusdua as $dsd){
            $iddbr = $dsd->iddbr;
            //$tanggalkirimunit = $dsd->tanggalpengajuanunit;
            $tanggalkirimunitolah = $dsd->tanggalpengajuanunit;
            $tanggalkirimunitolah = Carbon::parse($tanggalkirimunitolah);
            $tanggalperingatan = $tanggalkirimunitolah->addDay(5);
            $selisihhari = $tanggalperingatan->diffInDays($tanggalsekarang);
            if ($selisihhari == 0){
                $this->ingatkanunit($iddbr);
            }
        }
    }

    public function aksifinalotomatis(){
        $dbrstatusdua = DB::table('dbrinduk')
            ->where('statusdbr','=',2)
            ->get();
        $tanggalsekarang = Carbon::now();
        foreach ($dbrstatusdua as $dsd){
            $iddbr = $dsd->iddbr;
            //$tanggalkirimunit = $dsd->tanggalpengajuanunit;
            $tanggalkirimunitolah = $dsd->tanggalpengajuanunit;
            $tanggalkirimunitolah = Carbon::parse($tanggalkirimunitolah);
            $tanggalfinalisasi = $tanggalkirimunitolah->addDay(10);
            $selisihhari = $tanggalfinalisasi->diffInDays($tanggalsekarang);
            if ($selisihhari == 0){
                $this->finalisasiotomatisdbr($iddbr);
            }
        }
    }

    public function finalisasiotomatisdbr($iddbr){
        $dataupdate = array(
            'statusdbr' => 3,
            'tanggalpersetujuandbr' => Carbon::now(),
            'usersetujudbr' => 150,
            'dokumendbr' => 'DBRRuangan '.$iddbr.".pdf",
        );

        //update DBR nya
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

        //Cetak DBR nya
        $this->cetakdbrtanpadownload($iddbr);
    }

    public function ingatkanunit($iddbr){
        $idpenanggungjawab = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->value('idpenanggungjawab');
        $phonepenanggungjawab = DB::table('pegawai')->where('id','=',$idpenanggungjawab)->value('phone');
        $namapenanggungjawab = DB::table('pegawai')->where('id','=',$idpenanggungjawab)->value('nama');

        //data DBR
        $datadbr = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->get();
        $tanggalpengajuanunit = "";
        $tanggalpengajuanunitolah = "";
        $batasakhir = "";
        foreach ($datadbr as $item) {
            $tanggalpengajuanunitolah = $item->tanggalpengajuanunit;
            $tanggalpengajuanunitolah = Carbon::parse($tanggalpengajuanunitolah);
            $batasakhirasli = $item->batasakhir;
            if ($batasakhirasli == null){
                $batasakhir = $tanggalpengajuanunitolah->addDay(10);
                $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');
            }else{
                $batasakhir = Carbon::parse($batasakhirasli);
                $uraianbatasakhir = $batasakhir->isoFormat('D MMMM Y');
            }
            $tanggalpengajuanunit = $item->tanggalpengajuanunit;
            $tanggalpengajuanunit = Carbon::parse($tanggalpengajuanunit);
            $uraiantanggalpengajuanunit = $tanggalpengajuanunit->isoFormat('D MMMM Y');
        }


        //kirim notif
        $notif = new KirimWhatsapp();
        $notif = $notif->ingatkanunit($phonepenanggungjawab, $namapenanggungjawab, $iddbr, $uraiantanggalpengajuanunit, $uraianbatasakhir);
        if($notif == "Sukses"){
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function perubahanfinal($iddbr){
        $dbr = DB::table('dbrinduk')->where('iddbr','=',$iddbr);
        $adadbr = $dbr->count();
        $datadbr = $dbr->get();
        if ($adadbr > 0){
            foreach ($datadbr as $dbr){
                $versike = $dbr->versike;
                $idpenanggungjawab = $dbr->idpenanggungjawab;
                $idgedung = $dbr->idgedung;
                $idruangan = $dbr->idruangan;
                $statusdbr = $dbr->statusdbr;
                $dibuatoleh = $dbr->dibuatoleh;
                $dibuatpada = $dbr->dibuatpada;
                $useredit = $dbr->useredit;
                $terakhiredit = $dbr->terakhiredit;
                $tanggalpengajuanunit = $dbr->tanggalpengajuanunit;
                $tanggalpersetujuandbr = $dbr->tanggalpersetujuandbr;
                $versikeawal = $dbr->versike;
                $dokumendbr = $dbr->dokumendbr;

                $datainsert = array(
                    'iddbr' => $iddbr,
                    'idpenanggungjawab' => $idpenanggungjawab,
                    'idgedung' => $idgedung,
                    'idruangan' => $idruangan,
                    'statusdbr' => $statusdbr,
                    'dibuatoleh' => $dibuatoleh,
                    'dibuatpada' => $dibuatpada,
                    'useredit' => $useredit,
                    'terakhiredit' => $terakhiredit,
                    'tanggalpengajuanunit' => $tanggalpengajuanunit,
                    'tanggalpersetujuandbr' => $tanggalpersetujuandbr,
                    'versike' =>  $versikeawal,
                    'dokumendbr' => $dokumendbr
                );
                DB::table('historydbr')->insert($datainsert);

                //copy file dokumendbr ke dok history dbr
                Storage::copy('dbrfinal/'.$dokumendbr, 'historydbrfinal/'.$dokumendbr);

                //rubah status dbr induk
                $dataupdate = array(
                    'statusdbr' => 1,
                    'useredit' => Auth::id(),
                    'terakhiredit' => now(),
                    'versike' => $versike+1
                );
                DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);

            }
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function lihatdbr($iddbr){
        $judul = "Data Barang DBR";
        $datadbr = DB::table('dbrinduk as a')
            ->select(['b.uraiangedung as gedung','c.uraianruangan as ruangan'])
            ->leftJoin('gedung as b','a.idgedung','=','b.id')
            ->leftJoin('ruangan as c','a.idruangan','=','c.id')
            ->where('a.iddbr','=',$iddbr)
            ->get();
        foreach ($datadbr as $data){
            $gedung = $data->gedung;
            $ruangan = $data->ruangan;
        }
        return view('Sirangga.Admin.lihatdbr',[
            "judul"=>$judul,
            "iddbr" => $iddbr,
            "gedung" => $gedung,
            "ruangan" => $ruangan
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

    public function getdatabarangtambah(){
        $data = BarangModel::with('kodebarangrelation')
            ->select('barang.*')
            ->where('barang.kondisi','=',1)
            ->where('barang.statusdbr','=',1);

        return Datatables::eloquent($data)
            ->addColumn('ur_sskel', function (BarangModel $barang) {
                return $barang->kodebarangrelation->ur_sskel;
            })
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Pilih" class="btn btn-danger btn-sm pilihbarang">Pilih</a>';
                return $btn;
            })
            ->toJson();
    }

    public function insertbarangdipilih(Request $request){
        $iddbr = $request->get('iddbr');
        $idbarang = $request->get('idbarang');
        //cek apakah idbarng sudah ada di data detil
        $adabarang = DB::table('detildbr')->where('idbarang','=',$idbarang)->count();
        if ($adabarang == 0){
            $databarang = DB::table('barang')
                ->where('id','=',$idbarang)
                ->get();
            foreach ($databarang as $data){
                $kd_lokasi = $data->kd_lokasi;
                $kd_brg = $data->kd_brg;
                $no_aset = $data->no_aset;
                $tahunperolehan = date('Y',strtotime($data->tgl_perlh));
                $merek = $data->merk_type;
                $uraianbarang = DB::table('t_brg')->where('kd_brg','=',$kd_brg)->value('ur_sskel');

                $datainsert = array(
                    'iddbr' => $iddbr,
                    'idbarang' => $idbarang,
                    'kd_lokasi' => $kd_lokasi,
                    'kd_brg' => $kd_brg,
                    'no_aset' => $no_aset,
                    'uraianbarang' => $uraianbarang,
                    'tahunperolehan' => $tahunperolehan,
                    'merek' => $merek,
                    'statusbarcode' => 1,
                    'iduser' => Auth::id(),
                    'waktumasukdbr' => now(),
                    'waktukeluardbr' => null,
                    'statusbarang' => "Tidak Ada",
                    'terakhirperiksa' => null,
                    'diperiksaoleh' => null
                );
                DB::table('detildbr')->insert($datainsert);

                //rubah statusbarang
                DB::table('barang')->where('id','=',$idbarang)
                    ->update([
                        'statusdbr' => 2
                    ]);
            }
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function deletebarangdipilih(Request $request){
        $iddetil = $request->get('iddetil');
        $idbarang = DB::table('detildbr')->where('iddetil','=',$iddetil)->value('idbarang');

        //delete dari table detildbr
        DB::table('detildbr')->where('iddetil','=',$iddetil)->delete();

        //rubah status di barang
        DB::table('barang')->where('id','=',$idbarang)->update([
            'statusdbr' => 1
        ]);

        return response()->json(['status'=>'berhasil', with(['iddetil' => $iddetil])]);
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

    function databartenderexport($iddbr){
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new DataBartenderExport($iddbr),'DataBartender.xlsx');
    }

    function cekfisik($iddbr){
        //lakukan perubahan final dlu
        $this->perubahanfinal($iddbr);

        //lakukan prosedur cek fisik
        DB::table('detildbr')
            ->where('iddbr','=',$iddbr)
            ->update([
                'statusbarang' => "Tidak Ada",
                'terakhirperiksa' => null,
                'diperiksaoleh' => null
            ]);
    }





}
