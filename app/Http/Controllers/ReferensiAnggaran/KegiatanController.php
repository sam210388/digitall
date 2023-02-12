<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiAnggaran\KegiatanModel;
use App\Models\ReferensiAnggaran\ProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function kegiatan(){
        $judul = "List Kegiatan";
        return view('ReferensiAnggaran.kegiatan',[
            "judul"=>$judul
        ]);
    }

    public function getListKegiatan(Request $request){
        if ($request->ajax()) {
            $data = KegiatanModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function importkegiatan(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ADM';
        $tipedata = 'refUraian';
        $variable = ['kegiatan'];

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
            //echo json_encode($hasilasli);

            foreach ($hasilasli as $item => $value) {
                if ($item == "TOKEN") {
                    foreach ($value as $data) {
                        $tokenresponse = $data->TOKEN;
                    }
                    $token = new BearerKey();
                    $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                }
            }
            foreach ($hasilasli as $item => $value) {
                if ($item != "TOKEN") {
                    foreach ($value as $data) {
                        $THANG = $data->THANG;
                        $KODE = $data->KODE;
                        $DESKRIPSI = $data->DESKRIPSI;

                        $where = array(
                            'tahunanggaran' => $THANG,
                            'kode' => $KODE
                        );

                        $jumlah = DB::table('kegiatanall')->where($where)->get()->count();
                        if ($jumlah == 0) {
                            $data = array(
                                'tahunanggaran' => $THANG,
                                'kode' => $KODE,
                                'deskripsi' => $DESKRIPSI
                            );
                            DB::table('kegiatanall')->insert($data);
                        }else{
                            DB::table('kegiatanall')->where($where)->update($data);
                        }
                    }
                }
            }
            $this->importkegiatandpr();
            return redirect()->to('kegiatan')->with('status','Import Kegiatan Berhasil');
        }else if ($response == "Expired"){
                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('kegiatan')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('kegiatan')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }

    function importkegiatandpr(){
        $tahunanggaran = session('tahunanggaran');
        $datasatker = ['001012','001030'];
        $statusimport = "";

        foreach ($datasatker as $satker){
            //dapatkan data IDREFSTATUS terakhir
            $idrefstatus = DB::table('ref_status')
                ->where([
                    ['tahunanggaran','=',$tahunanggaran],
                    ['kd_sts_history','LIKE','B%'],
                    ['kdsatker','=',$satker]
                ])->orwhere([
                    ['kd_sts_history','LIKE','C%'],
                    ['kdsatker','=',$satker],
                    ['tahunanggaran','=',$tahunanggaran],
                    ['flag_update_coa','=',1]])
                ->max('idrefstatus');

            //dapatkan info kd_sts_history
            //$kd_sts_history = DB::table('ref_status')->where('idrefstatus','=',$idrefstatus)->value('kd_sts_history');

            //dapatkan data anggaran
            $dataanggaran = DB::table('data_ang')
                ->where('idrefstatus','=',$idrefstatus)
                ->get();
            //echo $dataanggaran;

            if (count($dataanggaran) === 0) {
                $statusimport = $statusimport.$satker." Data Ang Terakhir Belum Diimport ";

            }else{
                foreach ($dataanggaran as $item){
                    $tahunanggaran = $item->tahunanggaran;
                    $kodekegiatan = $item->kodekegiatan;
                    $uraiankegiatan = DB::table('kegiatanall')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kode','=',$kodekegiatan)
                        ->value('deskripsi');

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kode' => $kodekegiatan,
                        'deskripsi' => $uraiankegiatan
                    );

                    KegiatanModel::updateOrCreate([
                        'tahunanggaran' => $tahunanggaran,
                        'kode' => $kodekegiatan
                    ],$data);
                }
            }
        }
    }
}
