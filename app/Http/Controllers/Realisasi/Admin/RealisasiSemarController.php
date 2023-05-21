<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\CekPengenal;
use App\Libraries\FilterDataUser;
use App\Libraries\PeriodeLaporan;
use App\Models\ReferensiUnit\BagianModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminAnggaran\AnggaranBagianModel;
use Yajra\DataTables\DataTables;

class RealisasiSemarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function realisasisemar(Request $request)
    {
        $judul = 'Data Realisasi SEMAR';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $wheredata= new FilterDataUser();
            $wheredata = $wheredata->filterdata();
            if (count($wheredata)>0){
                $wheretambahan = $wheredata;
            }else{
                $wheretambahan = array();
            }
            $data = DB::table('realisasisemar')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('pengenal','!=',null)
                ->where($wheretambahan)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('idbagian',function ($row){
                    $idbagian = $row->idbagian;
                    $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbagian;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->make(true);
        }

        return view('Realisasi.admin.realisasisemar',[
            "judul"=>$judul,
        ]);
    }

    public function importrealisasisemar(){
        $tahunanggaran = session('tahunanggaran');
        //ambil bulan sekarang
        $tanggalserver = new DateTime();
        $bulan = $tanggalserver->format('m');

        //rubah formatnya jd format semar
        $periodelaporan = New PeriodeLaporan();
        $periodelaporan = $periodelaporan->tanggallaporan($tahunanggaran, 1,$bulan);
        $token = array(
            'token' => 'samwitwicky'
        );

        $curlvariabel = array_merge($token, $periodelaporan);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://semar.dpr.go.id/api/karwas-pengenal',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($curlvariabel),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: PHPSESSID=kcvpdgh1e8p95he18a2n9i81p6'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        DB::table('realisasisemar')->truncate();

        $hasil = json_decode($response);
        foreach ($hasil as $item){
            $tanggal_spp_spby = $item->tanggal_spp_spby;
            $no_kwitansi_karwas = $item->no_kwitansi_karwas;
            $no_spp = $item->no_spp;
            $no_spby = $item->no_spby;
            $id_anak_satker = $item->id_anak_satker;
            $pengenal = $item->pengenal;
            $cekpengenal = new CekPengenal();
            $idbagian = $cekpengenal->cekbagian($pengenal);
            $kdsatker = $cekpengenal->satkerpemilik($pengenal);
            $idbiro = $cekpengenal->cekbiro($pengenal);
            $iddeputi = $cekpengenal->cekdeputi($pengenal);
            $nama_rekanan = $item->nama_rekanan;
            $uraian_pekerjaan = $item->uraian_pekerjaan;
            $nilai_tagihan = $item->nilai_tagihan;
            $cara_bayar = $item->cara_bayar;
            $tanggal_pembayaran_kasbon = $item->tanggal_pembayaran_kasbon;
            $nama_penerima = $item->nama_penerima;
            $no_kwitansi_silabi = $item->no_kwitansi_silabi;
            $no_pembukuan_kwitansi_silabi = $item->no_pembukuan_kwitansi_silabi;
            $no_pajak_silabi = $item->no_pajak_silabi;
            $no_pembukuan_pajak_silabi = $item->no_pembukuan_pajak_silabi;
            $no_spm = $item->no_spm;
            $tanggal_spm = $item->tanggal_spm;
            $tanggal_sp2d = $item->tanggal_sp2d;
            $no_sp2d = $item->no_sp2d;
            $tanggal_kwitansi_karwas = $item->tanggal_kwitansi_karwas;
            $tahapan = $item->tahapan;

            $datainsert = array(
                'tahunanggaran' => $tahunanggaran,
                'tanggal_spp_spby' => $tanggal_spp_spby,
                'no_kwitansi_karwas' => $no_kwitansi_karwas,
                'no_spp' => $no_spp,
                'no_spby' => $no_spby,
                'id_anak_satker' => $id_anak_satker,
                'pengenal' => $pengenal,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagian,
                'idbiro' => $idbiro,
                'iddeputi' => $iddeputi,
                'nama_rekanan' => $nama_rekanan,
                'uraian_pekerjaan' => $uraian_pekerjaan,
                'nilai_tagihan' => $nilai_tagihan,
                'cara_bayar' => $cara_bayar,
                'tanggal_pembayaran_kasbon' => $tanggal_pembayaran_kasbon,
                'nama_penerima' => $nama_penerima,
                'no_kwitansi_silabi' => $no_kwitansi_silabi,
                'no_pembukuan_kwitansi_silabi' => $no_pembukuan_kwitansi_silabi,
                'no_pajak_silabi' => $no_pajak_silabi,
                'no_pembukuan_pajak_silabi' => $no_pembukuan_pajak_silabi,
                'no_spm' => $no_spm,
                'tanggal_spm' => $tanggal_spm,
                'tanggal_sp2d' => $tanggal_sp2d,
                'no_sp2d' => $no_sp2d,
                'tanggal_kwitansi_karwas' => $tanggal_kwitansi_karwas,
                'tahapan' => $tahapan

            );
            DB::table('realisasisemar')->insert($datainsert);
        }
        return redirect()->to('realisasisemar')->with('status','Import Realisasi SEMAR Berhasil');
    }
}
