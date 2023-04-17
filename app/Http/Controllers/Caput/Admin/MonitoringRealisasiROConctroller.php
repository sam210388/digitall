<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caput\Biro\RealisasiROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonitoringRealisasiROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasiro(){
        $judul = 'List Realisasi RO';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.monitoringrealisasiro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro
            //"data" => $data

        ]);

    }

    public function getdatarealisasiro(Request $request, $idbulan, $idbiro = null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        if ($request->ajax()) {
            $data = DB::table('ro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput," | ",a.uraianro) as ro'), 'a.target as target','a.idkro as idkro',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan','b.status as statusrealisasi',
                    'e.uraiankro as kro','a.id as idro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasiro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('kro as e', 'a.idkro', '=', 'e.id')
                ->where('a.tahunanggaran', '=', $tahunanggaran);

            if ($idbiro != null){
                $data->where('a.idbiro','=',$idbiro);
            }

            $data = $data->groupBy('a.id');
            $data = $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idro . '" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
                    return $btn;
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

    public function cekjadwallapor($idro, $idbulan){
        $kondisilapor = "";
        $tahunanggaran = session('tahunanggaran');
        //cekrealisasisebelumnya
        if ($idbulan == 1){
            $laporsebelumnya = true;
        }else{
            $adarealisasi = DB::table('realisasiro')
                ->where('idro','=',$idro)
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
            ->where('jenislaporan','=',2)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbulan','=',$idbulan)
            ->value('jadwalbuka');
        $jadwaltutup = DB::table('jadwaltutup')
            ->where('jenislaporan','=',2)
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
            if ($tanggalsekarang < $jadwaltutup){
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

    function rekaprealisasiro(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idro = $request->get('idro');
        $idbulan = $request->get('nilaibulan');
        $tanggallapor = date('Y-m-d');

        //dapatkan target dan jenis indikatorro
        $targetro = "";
        $jenisindikator = "";
        $idkro = "";
        $dataro = DB::table('ro')
            ->where('id','=',$idro)
            ->get();
        foreach ($dataro as $dir){
            $targetro = $dir->target;
            $jenisindikator = $dir->jenisindikator;
            $idkro = $dir->idkro;
        }

        //dapatkan data periode sebelumnya
        $jumlahsdperiodesebelumnya = "";
        $prosentasesdperiodesebelumnya = "";
        if ($idbulan == 1){
            $jumlahsdperiodesebelumnya = 0;
            $prosentasesdperiodesebelumnya = 0.00;
        }else{
            $datarealisasisebelumnya = DB::table('realisasiro')
                ->where('idro','=',$idro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan-1)
                ->get();
            foreach ($datarealisasisebelumnya as $drs){
                $jumlahsdperiodesebelumnya = $drs->jumlahsdperiodeini;
                $prosentasesdperiodesebelumnya = $drs->prosentasesdperiodeini;
            }
        }

        if ($jenisindikator == 1 and $idbulan == 12){
            $jumlah = 1;
            $jumlahsdperiodeini = 1;
            $prosentase = 8.33;
            $prosentasesdperiodeini = 100;
        }else if ($jenisindikator == 1 and $idbulan != 12){
            $jumlah = 0;
            $jumlahsdperiodeini = 0;
            $prosentase = 8.33;
            $prosentasesdperiodeini = $idbulan * 8.33;
        }else{
            $jumlah = DB::table('realisasiindikatorro')
                ->select([DB::raw('sum(jumlah) as jumlah')])
                ->where('idro','=',$idro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->value('jumlah');
            $jumlahsdperiodeini = $jumlahsdperiodesebelumnya+$jumlah;
            $prosentase = $this->rekapprosentasero($idro, $tahunanggaran, $idbulan, $targetro);
            $prosentase = round($prosentase,2);
            $prosentasesdperiodeini = $prosentasesdperiodesebelumnya+$prosentase;

        }

        //dapatkan statusterbanyak
        $statuspelaksanaan = $this->dapatkanstatuspelaksanaanterbanyak($idro, $tahunanggaran, $idbulan);

        //dapatkan kategori permasalahan terbanyak
        $kategoripermasalahan = $this->dapatkankategoripermasalahanterbanyak($idro, $tahunanggaran, $idbulan);

        //dapatkan uraian output dihasilkan
        $uraianoutputdihasilkan = $this->dapatkanuraianoutputdihasilkan($idro, $tahunanggaran, $idbulan);

        //dapatkan keterangan
        $keterangan = $this->dapatkanketerangan($idro, $tahunanggaran, $idbulan);

        $data = array(
            'idro' => $idro,
            'idkro' => $idkro,
            'tahunanggaran' => $tahunanggaran,
            'periode' => $idbulan,
            'tanggallapor' => $tanggallapor,
            'jumlah' => $jumlah,
            'jumlahsdperiodeini' => $jumlahsdperiodeini,
            'prosentase' => $prosentase,
            'prosentasesdperiodeini' => $prosentasesdperiodeini,
            'statuspelaksanaan' => $statuspelaksanaan,
            'kategoripermasalahan' => $kategoripermasalahan,
            'keterangan' => $keterangan,
            'uraianoutputdihasilkan' => $uraianoutputdihasilkan,
            'status' => 1
        );
        //cek apakah sudah ada realisasi
        $adarealisasi = DB::table('realisasiro')
            ->where('idro','=',$idro)
            ->where('periode','=',$idbulan)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->count();
        if ($adarealisasi == 0){
            DB::table('realisasiro')->insert($data);
        }else{
            DB::table('realisasiro')
                ->where('idro','=',$idro)
                ->where('periode','=',$idbulan)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->delete();
            DB::table('realisasiro')->insert($data);
        }
        return response()->json(['status'=> 'Rekap Realisasi Berhasil']);

    }

    function rekapprosentasero($idro, $tahunanggaran, $idbulan, $targetro){
        $prosentasero = 0;
        $dataindikatorro = DB::table('indikatorro')
            ->where('idro','=',$idro)
            ->get();
        foreach ($dataindikatorro as $dri){
            $idindikatorro = $dri->id;
            $target = $dri->target;

            //dapatkan realisasi prosentase dari masing masing realisasi
            $prosentase = DB::table('realisasiindikatorro')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->where('idindikatorro','=',$idindikatorro)
                ->value('prosentase');

            $porsiprosentase = ($target/$targetro) * $prosentase;
            $prosentasero = $prosentasero + $porsiprosentase;
        }
        return $prosentasero;
    }

    function dapatkanstatuspelaksanaanterbanyak($idro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasiindikatorro')
            ->select(['statuspelaksanaan',DB::raw('count(statuspelaksanaan) as statusterbanyak')])
            ->where('idro','=',$idro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('statusterbanyak','desc')
            ->value('statusterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkankategoripermasalahanterbanyak($idro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasiindikatorro')
            ->select(['kategoripermasalahan',DB::raw('count(kategoripermasalahan) as kategoriterbanyak')])
            ->where('idro','=',$idro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('kategoriterbanyak','desc')
            ->value('kategoriterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkanuraianoutputdihasilkan($idro, $tahunanggaran, $idbulan){
        $uraianoutputdihasilkan = DB::table('realisasiindikatorro')
            ->select([DB::raw('group_concat(uraianoutputdihasilkan) as uraianoutputdihasilkan')])
            ->where('idro','=',$idro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('uraianoutputdihasilkan');
        return $uraianoutputdihasilkan;
    }

    function dapatkanketerangan($idro, $tahunanggaran, $idbulan){
        $keterangan = DB::table('realisasiindikatorro')
            ->select([DB::raw('group_concat(keterangan) as keterangan')])
            ->where('idro','=',$idro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('keterangan');
        return $keterangan;
    }

}
