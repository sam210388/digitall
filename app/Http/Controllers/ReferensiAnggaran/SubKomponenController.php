<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiAnggaran\KomponenModel;
use App\Models\ReferensiAnggaran\SubKomponenModel;
use App\Models\ReferensiAnggaran\SubOutputModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SubKomponenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ambildatasubkomponen(Request $request){
        $data['subkomponen'] = DB::table('subkomponen')
            ->where('kodekegiatan','=',$request->kodekegiatan)
            ->where('kodeoutput','=',$request->kodeoutput)
            ->where('kodesuboutput','=',$request->kodesuboutput)
            ->where('kodekomponen','=',$request->kodekomponen)
            ->get(['kodesubkomponen','deskripsi']);

        return response()->json($data);
    }

    function importsubkomponen(){
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
                    $kodekegiatan = $item->kodekegiatan;
                    $kodeoutput = $item->kodeoutput;
                    $kodesuboutput = $item->kodesuboutput;
                    $kodekomponen = $item->kodekomponen;
                    $kodesubkomponen = $item->kodesubkomponen;
                    $uraiansubkomponen = $item->uraiansubkomponen;

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'kodekegiatan' => $kodekegiatan,
                        'kodeoutput' => $kodeoutput,
                        'kodesuboutput' => $kodesuboutput,
                        'kodekomponen' => $kodekomponen,
                        'kodesubkomponen' => $kodesubkomponen,
                        'deskripsi' => $uraiansubkomponen,
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen.$kodesubkomponen,
                        'jenisindikator' => 2,
                        'status' => "Dalam Proses"
                    );

                    SubKomponenModel::updateOrCreate([
                        'tahunanggaran' => $tahunanggaran,
                        'kode' => $kodekegiatan.".".$kodeoutput.".".$kodesuboutput.".".$kodekomponen.".".$kodesubkomponen
                    ],$data);
                }
                $statusimport = $statusimport.$satker." SubKomponen Berhasil Diimport ";
            }
        }
        return redirect()->to('subkomponen')->with('status',$statusimport);
    }

    function subkomponen(){
        $judul = "List SubKomponen";
        return view('ReferensiAnggaran.subkomponen',[
            "judul"=>$judul
        ]);
    }

    public function getListSubKomponen(Request $request){
        if ($request->ajax()) {
            $data = SubKomponenModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


}
