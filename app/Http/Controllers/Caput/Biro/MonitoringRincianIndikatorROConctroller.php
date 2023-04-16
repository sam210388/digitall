<?php

namespace App\Http\Controllers\Caput\Biro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonitoringRincianIndikatorROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasirincianindikatorro(){
        $judul = 'List Realisasi Rincian Indikator RO';
        $databulan = DB::table('bulan')->get();
        $datastatuspelaksanaan = DB::table('statuspelaksanaan')->get();
        $datakategoripermasalahan = DB::table('kategoripermasalahan')->get();
        $idbiro = Auth::user()->idbiro;
        $databagian = DB::table('bagian')->where('idbiro','=',$idbiro)->get();

        return view('Caput.Biro.monitoringrincianindikatorro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan,
            "databagian" => $databagian
            //"data" => $data

        ]);

    }

    public function getdatarealisasi(Request $request, $idbulan, $idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data = DB::table('rincianindikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen,".",a.kodesubkomponen," | ",
                    a.uraianrincianindikatorro) as rincianindikatorro'), 'a.target as target',
                    'b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan', 'b.file as file',
                    'b.status as statusrealisasi', 'e.uraianindikatorro as indikatorro',
                    'a.id as idrincianindikatorro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasirincianindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idrincianindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('indikatorro as e', 'a.idindikatorro', '=', 'e.id')
                ->where('a.idbiro', '=', $idbiro)
                ->where('a.tahunanggaran', '=', $tahunanggaran);

            if ($idbagian != null){
                if ($idbagian == "BIRO"){
                    $data->whereNull('a.idbagian');
                }else{
                    $data->where('a.idbagian','=',$idbagian);
                }
            }


            $data = $data->groupBy('a.id')
                ->get(['indikatorro', 'rincianindikatorro', 'target', 'jumlah', 'jumlahsdperiodeini', 'prosentase',
                    'prosentasesdperiodeini', 'statuspelaksanaan', 'kategoripermasalahan', 'uraianoutputdihasilkan',
                    'keterangan', 'file', 'statusrealisasi']);


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->statusrealisasi == 3) {
                        $id = $row->idrealisasi."/".$row->idrincianindikatorro;
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm batalvalidasi">Batal Validasi</a>';
                        return $btn;
                    } else {
                        $btn = '';
                        return $btn;
                    }
                })
                ->addColumn('statusrealisasi', function ($row) {
                    $idstatus = $row->statusrealisasi;
                    $uraianstatus = DB::table('statusrealisasi')
                        ->where('id','=',$idstatus)
                        ->value('uraianstatus');
                    return $uraianstatus;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function batalvalidasirincianindikator(Request $request){
        $idrealisasi = $request->get('idrealisasi');
        DB::table('realisasirincianindikatorro')
            ->where('id','=',$idrealisasi)
            ->update([
                'status' => 1
            ]);
        return response()->json(['status'=> "berhasil"]);

    }

    public function cekjadwallapor($idrincianindikatorro, $idbulan){
        $kondisilapor = "";
        $tahunanggaran = session('tahunanggaran');
        //cekrealisasisebelumnya
        if ($idbulan == 1){
            $laporsebelumnya = true;
        }else{
            $adarealisasi = DB::table('realisasirincianindikatorro')
                ->where('idrincianindikatorro','=',$idrincianindikatorro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan-1)
                ->get();
            if (count($adarealisasi) == 0){
                $laporsebelumnya = false;
                $kondisi = "Realisasi Sebelumnya: Belum Diisi";
                $kondisilapor = $kondisilapor.$kondisi;
            }else{
                $laporsebelumnya = true;
            }
        }

        //cek jadwal lapor
        $jadwalbuka = DB::table('jadwaltutup')
            ->where('jenislaporan','=',1)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbulan','=',$idbulan)
            ->value('jadwalbuka');
        $jadwaltutup = DB::table('jadwaltutup')
            ->where('jenislaporan','=',1)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbulan','=',$idbulan)
            ->value('jadwaltutup');
        if ($jadwalbuka == null){
            $statusbuka = false;
            $kondisi = " Jadwal Buka: Belum Ditetapkan";
            $kondisilapor = $kondisilapor.$kondisi;
        }else{
            $tanggalsekarang = strtotime(date('Y-m-d'));
            $jadwalbuka = strtotime($jadwalbuka);
            if ($tanggalsekarang < $jadwalbuka){
                $statusbuka = false;
                $kondisi = "Jadwal Buka: Belum Dibuka";
                $kondisilapor = $kondisilapor.$kondisi;
            }else{
                $statusbuka = true;
            }
        }

        $statustutup = "";
        if ($jadwaltutup != null){
            $tanggalsekarang = strtotime(date('Y-m-d'));
            $jadwaltutup = strtotime($jadwaltutup);
            if ($tanggalsekarang <= $jadwaltutup){
                $statustutup = false;
            }else{
                $statustutup = true;
                $kondisi = "Jadwal Tutup: Sudah Tutup";
                $kondisilapor = $kondisilapor.$kondisi;
            }
        }

        if ($laporsebelumnya && $statusbuka && !$statustutup){
            $status = "Buka";
        }else{
            $status = "Tutup";
        }
        return response()->json(['status'=> $status,'kondisi' => $kondisilapor]);
    }

}
