<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Jobs\DeleteDataAng;
use App\Jobs\ImportDataAng;
use App\Jobs\RekonDataAng;
use App\Jobs\UpdateBagian;
use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\AdminAnggaran\AnggaranBagianModel;
use App\Models\AdminAnggaran\DataAngModel;
use App\Models\AdminAnggaran\SummaryDipaModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DataAngController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function checkdata(Request $request){
        $kdsatker = $request->get('kdsatker');
        $kd_sts_history = $request->get('kd_sts_history');
        $jumlahdata = DB::table('data_ang')
            ->where($kdsatker,'=',$kdsatker)
            ->where('kodestshistory','=',$kd_sts_history)
            ->count();

        if ($jumlahdata > 0){
            return response()->json("Ada");
        }else{
            return response()->json("Tidak Ada");
        }
    }


    public function aksideleteanggaran($idrefstatus){
        DataAngModel::where('idrefstatus',$idrefstatus)->delete();
    }


    function importdataang($idrefstatus){
        $tahunanggaran = session('tahunanggaran');
        //$this->aksiimportdataang($tahunanggaran, $idrefstatus);

        Bus::chain([
            new DeleteDataAng($idrefstatus),
            new ImportDataAng($tahunanggaran, $idrefstatus),
            new RekonDataAng($idrefstatus)
        ])->dispatch();
        return redirect()->to('refstatus')->with('prosesimport','Proses Import Berhasil, tunggu beberapa saat untuk melakukan pengecekan');

    }

    function aksiimportdataang($tahunanggaran, $idrefstatus){
        $kodemodul = 'ANG';
        $tipedata = 'dataAng';

        // Reset API
        $resetapi = new BearerKey();
        $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        // Mendapatkan variabel kdsatker dan kd_sts_history dari database
        $refStatus = DB::table('ref_status')->where('idrefstatus', $idrefstatus)->first();
        if (!$refStatus) {
            // Handle jika refStatus tidak ditemukan
            throw new Exception("RefStatus dengan ID $idrefstatus tidak ditemukan.");
        }

        $kdsatker = $refStatus->kdsatker;
        $kd_sts_history = $refStatus->kd_sts_history;
        $variable = [$kdsatker, $kd_sts_history];

        // Tarik data
        $tarikData = new TarikDataMonsakti();
        $response = $tarikData->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);

        if ($response === "Gagal" || $response === "Expired" || empty($response)) {
            // Handle jika response tidak valid
            throw new Exception("Gagal menarik data: $response");
        }

        $hasilasli = json_decode($response);
        if (!is_array($hasilasli)) {
            // Handle jika hasil decode bukan array
            throw new Exception("Format response tidak valid.");
        }

        foreach ($hasilasli as $innerArray) {
            if (!is_array($innerArray)) continue;

            foreach ($innerArray as $item) {
                if (isset($item->TOKEN)) {
                    // Simpan TOKEN baru
                    $tokenresponse = $item->TOKEN;
                    $token = new BearerKey();
                    $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                } else {
                    // Proses dan simpan data ke database
                    $data = [
                        'tahunanggaran' => $tahunanggaran,
                        'idrefstatus' => $idrefstatus,
                        'kdsatker' => $item->KDSATKER ?? null,
                        'kodeprogram' => $item->KODE_PROGRAM ?? null,
                        'kodekegiatan' => $item->KODE_KEGIATAN ?? null,
                        'kodeoutput' => $item->KODE_OUTPUT ?? null,
                        'kdib' => $item->KDIB ?? null,
                        'volumeoutput' => $item->VOLUME_OUTPUT ?? null,
                        'kodesuboutput' => $item->KODE_SUBOUTPUT ?? null,
                        'volumesuboutput' => $item->VOLUME_SUBOUTPUT ?? null,
                        'kodekomponen' => $item->KODE_KOMPONEN ?? null,
                        'kodesubkomponen' => $item->KODE_SUBKOMPONEN ?? null,
                        'uraiansubkomponen' => $item->URAIAN_SUBKOMPONEN ?? null,
                        'kodeakun' => $item->KODE_AKUN ?? null,
                        'pengenal' => implode('.', [
                            $tahunanggaran,
                            $kdsatker,
                            $item->KODE_PROGRAM ?? '',
                            $item->KODE_KEGIATAN ?? '',
                            $item->KODE_OUTPUT ?? '',
                            $item->KODE_SUBOUTPUT ?? '',
                            $item->KODE_KOMPONEN ?? '',
                            $item->KODE_SUBKOMPONEN ?? '',
                            $item->KODE_AKUN ?? ''
                        ]),
                        'kodejenisbeban' => $item->KODE_JENIS_BEBAN ?? null,
                        'kodecaratarik' => $item->KODE_CARA_TARIK ?? null,
                        'header1' => $item->HEADER1 ?? null,
                        'header2' => $item->HEADER2 ?? null,
                        'kodeitem' => $item->KODE_ITEM ?? null,
                        'nomoritem' => $item->NOMOR_ITEM ?? null,
                        'cons_item' => $item->CONS_ITEM ?? null,
                        'uraianitem' => $item->URAIAN_ITEM ?? null,
                        'sumberdana' => $item->SUMBER_DANA ?? null,
                        'volkeg1' => isset($item->VOL_KEG_1) ? (int)$item->VOL_KEG_1 : 0,
                        'satkeg1' => $item->SAT_KEG_1 ?? null,
                        'volkeg2' => isset($item->VOL_KEG_2) ? (int)$item->VOL_KEG_2 : 0,
                        'satkeg2' => $item->SAT_KEG_2 ?? null,
                        'volkeg3' => isset($item->VOL_KEG_3) ? (int)$item->VOL_KEG_3 : 0,
                        'satkeg3' => $item->SAT_KEG_3 ?? null,
                        'volkeg4' => isset($item->VOL_KEG_4) ? (int)$item->VOL_KEG_4 : 0,
                        'satkeg4' => $item->SAT_KEG_4 ?? null,
                        'volkeg' => isset($item->VOLKEG) ? (int)$item->VOLKEG : 0,
                        'satkeg' => $item->SATKEG ?? null,
                        'hargasat' => isset($item->HARGASAT) ? (int)$item->HARGASAT : 0,
                        'total' => isset($item->TOTAL) ? (int)$item->TOTAL : 0,
                        'kodeblokir' => $item->KODE_BLOKIR ?? null,
                        'nilaiblokir' => isset($item->NILAI_BLOKIR) ? (int)$item->NILAI_BLOKIR : 0,
                        'kodestshistory' => $item->KODE_STS_HISTORY ?? null,
                        'poknilai1' => isset($item->POK_NILAI_1) ? (int)$item->POK_NILAI_1 : 0,
                        'poknilai2' => isset($item->POK_NILAI_2) ? (int)$item->POK_NILAI_2 : 0,
                        'poknilai3' => isset($item->POK_NILAI_3) ? (int)$item->POK_NILAI_3 : 0,
                        'poknilai4' => isset($item->POK_NILAI_4) ? (int)$item->POK_NILAI_4 : 0,
                        'poknilai5' => isset($item->POK_NILAI_5) ? (int)$item->POK_NILAI_5 : 0,
                        'poknilai6' => isset($item->POK_NILAI_6) ? (int)$item->POK_NILAI_6 : 0,
                        'poknilai7' => isset($item->POK_NILAI_7) ? (int)$item->POK_NILAI_7 : 0,
                        'poknilai8' => isset($item->POK_NILAI_8) ? (int)$item->POK_NILAI_8 : 0,
                        'poknilai9' => isset($item->POK_NILAI_9) ? (int)$item->POK_NILAI_9 : 0,
                        'poknilai10' => isset($item->POK_NILAI_10) ? (int)$item->POK_NILAI_10 : 0,
                        'poknilai11' => isset($item->POK_NILAI_11) ? (int)$item->POK_NILAI_11 : 0,
                        'poknilai12' => isset($item->POK_NILAI_12) ? (int)$item->POK_NILAI_12 : 0,
                        'urutan_header1' => $item->URUTAN_HEADER1 ?? null,
                        'urutan_header2' => $item->URUTAN_HEADER2 ?? null,
                        'kode_kppn' => $item->KODE_KPPN ?? null,
                        'kode_kewenangan' => $item->KODE_KEWENANGAN ?? null,
                        'kode_lokasi' => $item->KODE_LOKASI ?? null,
                    ];

                    // Simpan data ke database
                    DataAngModel::create($data);
                }
            }
        }

        // Update statusimport di ref_status
        DB::table('ref_status')->where('idrefstatus', $idrefstatus)->update(['statusimport' => 2]);
    }


    public function rekondataang($idrefstatus){
        $this->dispatch(new RekonDataAng($idrefstatus));
        return redirect()->to('refstatus')->with('rekonberhasil','Proses Rekon Data Anggaran Sedang Berlangsung, Mohon Refresh Halaman Secara Berkala');
    }

    public function aksirekondataang($idrefstatus){
        $jumlahpagudataang = DB::table('data_ang')
            ->select([DB::raw('sum(total) as total')])
            ->where('idrefstatus','=',$idrefstatus)
            ->where('header1','=',0)
            ->where('header2','=',0)
            ->value('total');

        DB::table('ref_status')->where('idrefstatus','=',$idrefstatus)
            ->update(['pagu_dataang' => $jumlahpagudataang]);
    }

    public function rekapanggarannoredirect($idrefstatus, $tahunanggaran){
        $datapagu = DataAngModel::where([
            ['header1','=',0],
            ['header2','=',0],
            ['idrefstatus','=',$idrefstatus]
        ])->get();

        foreach ($datapagu as $item){
            $kdsatker = $item->kdsatker;
            $kodeprogram = $item->kodeprogram;
            $kodekegiatan = $item->kodekegiatan;
            $kodeoutput = $item->kodeoutput;
            $kodesubout = $item->kodesuboutput;
            $kodekomponen = $item->kodekomponen;
            $kodesubkomponen = $item->kodesubkomponen;
            $kodeakun = $item->kodeakun;
            $jenisbelanja = substr($kodeakun,0,2);
            $pengenalanggaranbagian = $kodeprogram.'.'.$kodekegiatan.'.'.$kodeoutput.'.'.$kodesubout.'.'.$kodekomponen;
            $pengenalrealisasianggaran = $tahunanggaran.".".$kdsatker.".".$kodeprogram.'.'.$kodekegiatan.'.'.$kodeoutput.'.'.$kodesubout.'.'.$kodekomponen.".".$kodesubkomponen.".".$kodeakun;

            $indeks = $tahunanggaran.$kdsatker.$kodeprogram.$kodekegiatan.$kodeoutput.$kodesubout.$kodekomponen;

            if ($kdsatker == '001012'){
                $pengenalanggaranbagian = $pengenalanggaranbagian.".".$kodesubkomponen;
                $indeks = $indeks.$kodesubkomponen;
            }else{
                $pengenalanggaranbagian = $pengenalanggaranbagian;
                $indeks = $indeks;
            }

            $where = array(
                'indeks' => $indeks
            );
            $adadata = AnggaranBagianModel::where($where)->count();
            if ($adadata == 0){
                $data = array(
                    'tahunanggaran' => $tahunanggaran,
                    'kdsatker' => $kdsatker,
                    'kodeprogram' => $kodeprogram,
                    'kodekegiatan' => $kodekegiatan,
                    'kodeoutput' => $kodeoutput,
                    'kodesuboutput' => $kodesubout,
                    'kodekomponen' => $kodekomponen,
                    'kodesubkomponen' => $kodesubkomponen,
                    'pengenal' => $pengenalanggaranbagian,
                    'indeks' => $indeks,
                    'idbagian' => null
                );
                AnggaranBagianModel::insert($data);
            }

            //insert ke laporan realisasi anggaran
            $adadatarealisasi = DB::table('laporanrealisasianggaranbac')->where('pengenal','=',$pengenalrealisasianggaran)->count();
            if ($adadatarealisasi == 0){
                $datarealisasi = DB::table('anggaranbagian')->where('pengenal','=',$pengenalanggaranbagian)->get();
                $idbagian = 0;
                $idbiro = 0;
                $iddeputi = 0;
                foreach ($datarealisasi as $dr){
                    $idbagian = $dr->idbagian;
                    $idbiro = $dr->idbiro;
                    $iddeputi = $dr->iddeputi;
                    $idindikatorro = $dr->idindikatorro;
                    $idro = $dr->idro;
                    $idkro = $dr->idkro;
                }
                $datarealisasianggaran = array(
                    'tahunanggaran' => $tahunanggaran,
                    'kodesatker' => $kdsatker,
                    'kodeprogram' => $kodeprogram,
                    'kodekegiatan' => $kodekegiatan,
                    'kodeoutput' => $kodeoutput,
                    'kodesuboutput' => $kodesubout,
                    'kodekomponen' => $kodekomponen,
                    'kodesubkomponen' => $kodesubkomponen,
                    'kodeakun' => $kodeakun,
                    'jenisbelanja' => $jenisbelanja,
                    'pengenal' => $pengenalrealisasianggaran,
                    'statuspengenal' => 2,
                    'idbagian' => $idbagian,
                    'idbiro' => $idbiro,
                    'iddeputi' => $iddeputi,
                    'idindikatorro' => $idindikatorro,
                    'idro' => $idro,
                    'idkro' => $idkro
                );
                DB::table('laporanrealisasianggaranbac')->insert($datarealisasianggaran);
            }else{
                DB::table('laporanrealisasianggaranbac')->where('pengenal','=',$pengenalrealisasianggaran)->update([
                    'statuspengenal' => 2
                ]);
            }
        }
    }

    public function rekapanggaran($idrefstatus){
        $tahunanggaran = session('tahunanggaran');
        $this->rekapanggarannoredirect($idrefstatus, $tahunanggaran);
        return redirect()->to('refstatus')->with('rekapberhasil','Proses Update Unit Kerja ');
    }

    public function updatebagian(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new UpdateBagian($tahunanggaran));
        return redirect()->to('realisasipengenal')->with('updateberhasil','Update Unit Kerja Berhasil');
    }


    public function aksiupdatebagian($tahunanggaran){
        $data = DB::table('laporanrealisasianggaranbac')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($data as $d){
            $kodesatker = $d->kodesatker;
            $kodeprogram = $d->kodeprogram;
            $kodekegiatan = $d->kodekegiatan;
            $kodeoutput = $d->kodeoutput;
            $kodesuboutput = $d->kodesuboutput;
            $kodekomponen = $d->kodekomponen;
            $kodesubkomponen = $d->kodesubkomponen;

            if ($kodesatker == "001012"){
                $pengenal = $kodeprogram.".".$kodekegiatan.".".$kodeoutput.".".$kodesuboutput.".".$kodekomponen.".".$kodesubkomponen;
            }else{
                $pengenal = $kodeprogram.".".$kodekegiatan.".".$kodeoutput.".".$kodesuboutput.".".$kodekomponen;
            }

            //ID Bagian dan Biro
            $datapengenal = DB::table('anggaranbagian')->where('pengenal','=',$pengenal)->get();
            foreach ($datapengenal as $dp){
                $idbagian = $dp->idbagian;
                $idbiro = $dp->idbiro;
                $iddeputi = $dp->iddeputi;

                //update
                $where = array(
                    'tahunanggaran' => $tahunanggaran,
                    'kodesatker' => $kodesatker,
                    'kodeprogram' => $kodeprogram,
                    'kodekegiatan'=> $kodekegiatan,
                    'kodeoutput' => $kodeoutput,
                    'kodesuboutput' => $kodesuboutput,
                    'kodekomponen' => $kodekomponen,
                    'kodesubkomponen' => $kodesubkomponen
                );

                $dataupdate = array(
                    'idbagian' => $idbagian,
                    'idbiro' => $idbiro,
                    'iddeputi' => $iddeputi
                );
                DB::table('laporanrealisasianggaranbac')->where($where)->update($dataupdate);
            }
        }
    }




}
