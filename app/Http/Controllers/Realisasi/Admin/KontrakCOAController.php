<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportKontrakCOA;
use App\Jobs\ImportKontrakHeader;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\KontrakCOAModel;
use App\Models\Realisasi\Admin\KontrakHeaderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KontrakCOAController extends Controller
{
    public function kontrakheader(Request $request)
    {
        $judul = 'Data COA Kontrak';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('kontrakcoa');
            return Datatables::of($data)
                ->make(true);
        }

        return view('Realisasi.Admin.kontrakcoa',[
            "judul"=>$judul,
        ]);
    }

    function importkontrakcoa(){
        $kodesatker = ['001012','001030'];
        $tahunanggaran = session('tahunanggaran');
        foreach ($kodesatker as $kode){
            $this->dispatch(new ImportKontrakCOA($kode, $tahunanggaran));
        }
        return redirect()->to('kontrakcoa')->with('status','Import Kontrak COA dari SAKTI Berhasil');
    }

    function aksiimportkontrakcoa($TA){
        $kodesatker = ['001012','001030'];
        foreach ($kodesatker as $kode){
            $this->aksiimportkontrakcoapersatker($kode, $TA);
        }
    }

    function aksiimportkontrakcoapersatker($kodesatker, $tahunanggaran){
        $kodemodul = 'KOM';
        $tipedata = 'kontrakCOA';
        $variabel = [$kodesatker];

        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        //tarik data
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variabel);

        // Cek apakah response gagal atau expired
        if ($response != "Gagal" && $response != "Expired") {
            $hasilasli = json_decode($response, true); // Ubah menjadi array asosiatif

            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($hasilasli as $item => $value) {
                    // Cek jika item adalah token
                    if ($item == 0 && isset($value[0]['TOKEN'])) {
                        $tokenresponse = $value[0]['TOKEN'];
                        $token = new BearerKey();
                        $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                    }
                    // Jika bukan token, maka proses data kontrakCOA
                    elseif ($item == 1) {
                        // Hapus data lama sebelum memasukkan data baru
                        DB::table('kontrakCOA')
                            ->where('THNANG', '=', $tahunanggaran)
                            ->where('KDSATKER', '=', $kodesatker)
                            ->delete();

                        foreach ($value as $DATA) {
                            // Lakukan pengecekan jika properti yang dibutuhkan ada dalam JSON
                            if (isset($DATA['KODE_KEMENTERIAN'], $DATA['KDSATKER'], $DATA['ID_KONTRAK'],
                                $DATA['ID_LINE_KONTRAK'], $DATA['ID_JADWAL_PEMBAYARAN'],
                                $DATA['KODE_PROGRAM'], $DATA['KODE_KEGIATAN'],
                                $DATA['KODE_OUTPUT'], $DATA['KODE_AKUN'], $DATA['KODE_SUBOUTPUT'],
                                $DATA['KODE_KOMPONEN'], $DATA['KODE_SUBKOMPONEN'], $DATA['KODE_ITEM'],
                                $DATA['KODE_COA'], $DATA['VOL_SUBOUTPUT'], $DATA['NILAI_COA_DETAIL'])) {

                                $KODE_KEMENTERIAN = $DATA['KODE_KEMENTERIAN'];
                                $KDSATKER = $DATA['KDSATKER'];
                                $ID_KONTRAK = $DATA['ID_KONTRAK'];
                                $ID_LINE_KONTRAK = $DATA['ID_LINE_KONTRAK'];
                                $ID_JADWAL_PEMBAYARAN = $DATA['ID_JADWAL_PEMBAYARAN'];
                                $KODE_PROGRAM = $DATA['KODE_PROGRAM'];
                                $KODE_KEGIATAN = $DATA['KODE_KEGIATAN'];
                                $KODE_OUTPUT = $DATA['KODE_OUTPUT'];
                                $KODE_AKUN = $DATA['KODE_AKUN'];
                                $KODE_SUBOUTPUT = $DATA['KODE_SUBOUTPUT'];
                                $KODE_KOMPONEN = $DATA['KODE_KOMPONEN'];
                                $KODE_SUBKOMPONEN = substr($DATA['KODE_SUBKOMPONEN'], 1, 1);
                                $KODE_ITEM = $DATA['KODE_ITEM'];
                                $KODE_COA = $DATA['KODE_COA'];
                                $VOL_SUBOUTPUT = $DATA['VOL_SUBOUTPUT'];
                                $NILAI_COA_DETAIL = $DATA['NILAI_COA_DETAIL'];

                                $PENGENAL = $tahunanggaran . "." . $KDSATKER . "." . $KODE_PROGRAM . "." . $KODE_KEGIATAN . "." . $KODE_OUTPUT . "." . $KODE_SUBOUTPUT . "." . $KODE_KOMPONEN . "." . $KODE_SUBKOMPONEN . "." . $KODE_AKUN;

                                $IDBAGIAN = DB::table('laporanrealisasianggaranbac')->where('pengenal', '=', $PENGENAL)->value('idbagian');
                                $IDBIRO = DB::table('laporanrealisasianggaranbac')->where('pengenal', '=', $PENGENAL)->value('idbiro');

                                $data = array(
                                    'THNANG' => $tahunanggaran,
                                    'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                                    'KDSATKER' => $KDSATKER,
                                    'ID_KONTRAK' => $ID_KONTRAK,
                                    'ID_LINE_KONTRAK' => $ID_LINE_KONTRAK,
                                    'ID_JADWAL_PEMBAYARAN' => $ID_JADWAL_PEMBAYARAN,
                                    'KODE_PROGRAM' => $KODE_PROGRAM,
                                    'KODE_KEGIATAN' => $KODE_KEGIATAN,
                                    'KODE_OUTPUT' => $KODE_OUTPUT,
                                    'KODE_AKUN' => $KODE_AKUN,
                                    'KODE_SUBOUTPUT' => $KODE_SUBOUTPUT,
                                    'KODE_KOMPONEN' => $KODE_KOMPONEN,
                                    'KODE_SUBKOMPONEN' => $KODE_SUBKOMPONEN,
                                    'KODE_ITEM' => $KODE_ITEM,
                                    'KODE_COA' => $KODE_COA,
                                    'VOL_SUBOUTPUT' => $VOL_SUBOUTPUT,
                                    'NILAI_COA_DETAIL' => $NILAI_COA_DETAIL,
                                    'pengenal' => $PENGENAL,
                                    'idbagian' => $IDBAGIAN,
                                    'idbiro' => $IDBIRO
                                );

                                DB::table('kontrakcoa')->updateOrInsert([
                                    'THNANG' => $tahunanggaran,
                                    'ID_KONTRAK' => $ID_KONTRAK,
                                    'ID_LINE_KONTRAK' => $ID_LINE_KONTRAK,
                                    'ID_JADWAL_PEMBAYARAN' => $ID_JADWAL_PEMBAYARAN,
                                    'KODE_COA' => $KODE_COA
                                ],$data);
                            }
                        }
                    }
                }
            } else {
                // Error handling jika JSON tidak valid
                echo "JSON Error: " . json_last_error_msg();
            }
        } else {
            // Error jika response adalah "Gagal" atau "Expired"
            echo "Error: Response tidak valid.";
        }
    }


}
