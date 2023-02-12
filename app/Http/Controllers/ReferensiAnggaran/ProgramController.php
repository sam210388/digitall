<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiAnggaran\ProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function program(){
        $judul = "List Program";
        return view('ReferensiAnggaran.program',[
            "judul"=>$judul
        ]);
    }

    public function getListProgram(Request $request){
        if ($request->ajax()) {
            $data = ProgramModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function importprogram(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ADM';
        $tipedata = 'refUraian';
        $variable = ['program'];

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
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

                        $jumlah = DB::table('programall')->where($where)->get()->count();
                        if ($jumlah == 0) {
                            $data = array(
                                'tahunanggaran' => $THANG,
                                'kode' => $KODE,
                                'uraianprogram' => $DESKRIPSI
                            );
                            DB::table('programall')->insert($data);
                        }
                    }
                }
            }
            $this->importprogramdpr();
            return redirect()->to('program')->with('status','Import Program Berhasil');
        }else if ($response == "Expired"){
                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('program')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('program')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }

    function importprogramdpr(){
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
                //$importdata = new DataAngController();
                //$importdata = $importdata->importdataang($satker, $kd_sts_history);
            }else{
                foreach ($dataanggaran as $item){
                    $tahunanggaran = $item->tahunanggaran;
                    $kodesatker = $item->kdsatker;
                    $kodeprogram = $item->kodeprogram;
                    $uraianprogram = DB::table('programall')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kode','=',$kodeprogram)
                        ->value('uraianprogram');

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'kodeprogram' => $kodeprogram,
                        'uraianprogram' => $uraianprogram
                    );

                    ProgramModel::updateOrCreate([
                        'tahunanggaran' => $tahunanggaran,
                        'kode' => $kodeprogram
                    ],$data);
                }
            }
        }
    }
}
