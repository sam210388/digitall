<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Exports\ExportDetilRealisasi;
use App\Exports\ExportPenghapusanBarang;
use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\PenghapusanBarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class MonitoringPenghapusanBarangController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function penghapusanbarang(){
        $judul = "Data Penghapusan Barang";
        return view('Sirangga.Admin.penghapusanbarang',[
            "judul"=>$judul,
        ]);
    }

    public function getdatapenghapusanbarang(){
        $data = PenghapusanBarangModel::with('kodebarangrelation')
            ->select('penghapusanbarang.*');

        return (new \Yajra\DataTables\DataTables)->eloquent($data)
            ->addColumn('ur_sskel', function (PenghapusanBarangModel $barang) {
                return $barang->kodebarangrelation->ur_sskel;
            })
            ->toJson();

    }

    public function rekappenghapusanbarang(){
        $datapenghapusanbarang = DB::table('mastersakti')->where([
            'kdtrx' => 401
        ])->get();
        foreach ($datapenghapusanbarang as $DATA){
            $kode_kementerian = $DATA->kode_kementerian;
            $kdsatker = $DATA->kdsatker;
            $kduakpb = $DATA->kduakpb;
            $kd_gol = $DATA->kdgol;
            $kd_bid = $DATA->kdbid;
            $kd_kel = $DATA->kdkel;
            $kd_skel = $DATA->kdskel;
            $kd_brg = $DATA->kdbrg;
            $nup = $DATA->nup;
            $kondisi = $DATA->kondisi;
            $kdtrx = $DATA->kdtrx;
            $q_ast = $DATA->q_ast;
            $q_prb = $DATA->q_prb;
            $nilaiaset = $DATA->nilaiaset;
            $nilaiasetneraca = $DATA->nilaiasetneraca;
            $nilaiperubahan = $DATA->nilaiperubahan;
            $nilaiperubahanneraca = $DATA->nilaiperubahanneraca;
            $sisamasamanfaat = $DATA->sisamasamanfaat;
            $masamanfaat = $DATA->masamanfaat;
            $nosppa = $DATA->no_sppa;
            $status = $DATA->status;
            $kdsatkerasal = $DATA->kdsatkerasal;
            $kdregister = $DATA->kdregister;
            $keterangan = $DATA->keterangan;
            $no_dok = $DATA->no_dok;
            $jns_aset = $DATA->jns_aset;
            $periode = $DATA->periode;
            $merek_tipe = $DATA->merek_tipe;
            $catat = $DATA->catat;
            $thn_ang = $DATA->thn_ang;
            $created_date = $DATA->created_date;
            $created_by = $DATA->created_by;
            $tgl_buku = $DATA->tgl_buku;
            $tgl_oleh = $DATA->tgl_oleh;
            $tgl_awal_pakai = $DATA->tgl_awal_pakai;
            $kode_bast_kuitansi = $DATA->kode_bast_kuitansi;
            $no_bast_kuitansi = $DATA->no_bast_kuitansi;

            $data = array(
                'kode_kementerian' => $kode_kementerian,
                'kdsatker' => $kdsatker,
                'kduakpb' => $kduakpb,
                'kdgol' => $kd_gol,
                'kdbid' => $kd_bid,
                'kdkel' => $kd_kel,
                'kdskel' => $kd_skel,
                'kdbrg' => $kd_brg,
                'nup' => $nup,
                'kondisi' => $kondisi,
                'kdtrx' => $kdtrx,
                'q_ast' => $q_ast,
                'q_prb' => $q_prb,
                'nilaiaset' => $nilaiaset,
                'nilaiasetneraca' => $nilaiasetneraca,
                'nilaiperubahan' => $nilaiperubahan,
                'nilaiperubahanneraca' => $nilaiperubahanneraca,
                'sisamasamanfaat' => $sisamasamanfaat,
                'masamanfaat' => $masamanfaat,
                'no_sppa' => $nosppa,
                'status' => $status,
                'kdsatkerasal' => $kdsatkerasal,
                'kdregister' => $kdregister,
                'keterangan' => $keterangan,
                'no_dok' => $no_dok,
                'jns_aset' => $jns_aset,
                'periode' => $periode,
                'merek_tipe' => $merek_tipe,
                'catat' => $catat,
                'thn_ang' => $thn_ang,
                'created_date' => $created_date,
                'created_by' => $created_by,
                'tgl_buku' => $tgl_buku,
                'tgl_oleh' => $tgl_oleh,
                'tgl_awal_pakai' => $tgl_awal_pakai,
                'kode_bast_kuitansi' => $kode_bast_kuitansi,
                'no_bast_kuitansi' => $no_bast_kuitansi
            );
            PenghapusanBarangModel::updateOrCreate([
                'kduakpb' => $kduakpb,
                'kdbrg' => $kd_brg,
                'nup' => $nup
            ],[$data]);
        }
        return redirect()->to('penghapusanbarang')->with('status','Rekap Penghapusan Barang Berhasil');
    }

    function exportpenghapusanbarang(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportPenghapusanBarang($tahunanggaran),'PenghapusanBarang.xlsx');
    }



}
