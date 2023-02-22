<?php

namespace App\Http\Controllers\Caput\Biro;

use App\Http\Controllers\Controller;
use App\Models\Caput\Biro\RealisasiIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiIndikatorROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasiindikatorro(){
        $judul = 'List Realisasi Indikator RO';
        $databulan = DB::table('bulan')->get();
        $datastatuspelaksanaan = DB::table('statuspelaksanaan')->get();
        $datakategoripermasalahan = DB::table('kategoripermasalahan')->get();

        return view('Caput.Biro.realisasiindikatorro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan,
            //"data" => $data

        ]);

    }

    public function getdatarealisasiindikatorro(Request $request, $idbulan)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data = DB::table('indikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen," | ",
                    a.uraianindikatorro) as indikatorro'), 'a.target as target','a.idkro as idkro','a.idro as idro','e.uraianro as uraianro',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan',
                    'b.status as statusrealisasi', 'e.uraianro as ro',
                    'a.id as idindikatorro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasiindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('ro as e', 'a.idro', '=', 'e.id')
                ->where('a.idbiro', '=', $idbiro)
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->groupBy('a.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idindikatorro . '" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
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

    public function cekjadwallapor($idindikatorro, $idbulan){
        $kondisilapor = "";
        $tahunanggaran = session('tahunanggaran');
        //cekrealisasisebelumnya
        if ($idbulan == 1){
            $laporsebelumnya = true;
        }else{
            $adarealisasi = DB::table('realisasiindikatorro')
                ->where('idindikatorro','=',$idindikatorro)
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

    function rekaprealisasiindikatorro(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idindikatorro = $request->get('idindikatorro');
        $idbulan = $request->get('nilaibulan');
        $tanggallapor = date('Y-m-d');

        //dapatkan target dan jenis indikatorro
        $targetindikatorro = "";
        $jenisindikator = "";
        $idkro = "";
        $idro = "";
        $dataindikatorro = DB::table('indikatorro')
            ->where('id','=',$idindikatorro)
            ->get();
        foreach ($dataindikatorro as $dir){
            $targetindikatorro = $dir->target;
            $jenisindikator = $dir->jenisindikator;
            $idkro = $dir->idkro;
            $idro = $dir->idro;
        }

        //dapatkan data periode sebelumnya
        $jumlahsdperiodesebelumnya = "";
        $prosentasesdperiodesebelumnya = "";
        if ($idbulan == 1){
            $jumlahsdperiodesebelumnya = 0;
            $prosentasesdperiodesebelumnya = 0.00;
        }else{
            $datarealisasisebelumnya = DB::table('realisasiindikatorro')
                ->where('idindikatorro','=',$idindikatorro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan-1)
                ->get();
            foreach ($datarealisasisebelumnya as $datarealisasisebelumnya){
                $jumlahsdperiodesebelumnya = $datarealisasisebelumnya->jumlahsdperiodeini;
                $prosentasesdperiodesebelumnya = $datarealisasisebelumnya->prosentasesdperiodeini;
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
            $jumlah = DB::table('realisasirincianindikatorro')
                ->select([DB::raw('sum(jumlah) as jumlah')])
                ->where('idindikatorro','=',$idindikatorro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->value('jumlah');
            $jumlahsdperiodeini = $jumlahsdperiodesebelumnya+$jumlah;
            $prosentase = $this->rekapprosentaseindikatorro($idindikatorro, $tahunanggaran, $idbulan, $targetindikatorro);
            $prosentase = round($prosentase,2);
            $prosentasesdperiodeini = $prosentasesdperiodesebelumnya+$prosentase;

        }

        //dapatkan statusterbanyak
        $statuspelaksanaan = $this->dapatkanstatuspelaksanaanterbanyak($idindikatorro, $tahunanggaran, $idbulan);

        //dapatkan kategori permasalahan terbanyak
        $kategoripermasalahan = $this->dapatkankategoripermasalahanterbanyak($idindikatorro, $tahunanggaran, $idbulan);

        //dapatkan uraian output dihasilkan
        $uraianoutputdihasilkan = $this->dapatkanuraianoutputdihasilkan($idindikatorro, $tahunanggaran, $idbulan);

        //dapatkan keterangan
        $keterangan = $this->dapatkanketerangan($idindikatorro, $tahunanggaran, $idbulan);

        $data = array(
            'idindikatorro' => $idindikatorro,
            'idkro' => $idkro,
            'idro' => $idro,
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
        $adarealisasi = DB::table('realisasiindikatorro')
            ->where('idindikatorro','=',$idindikatorro)
            ->where('periode','=',$idbulan)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->count();
        if ($adarealisasi == 0){
            DB::table('realisasiindikatorro')->insert($data);
        }else{
            DB::table('realisasiindikatorro')
                ->where('idindikatorro','=',$idindikatorro)
                ->where('periode','=',$idbulan)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->delete();
            DB::table('realisasiindikatorro')->insert($data);
        }
        return response()->json(['status'=> 'Rekap Realisasi Berhasil']);

    }

    function rekapprosentaseindikatorro($idindikatorro, $tahunanggaran, $idbulan, $targetindikatorro){
        $prosentaseindikatorro = 0;
        $datarincianindikatorro = DB::table('rincianindikatorro')
            ->where('idindikatorro','=',$idindikatorro)
            ->get();
        foreach ($datarincianindikatorro as $dri){
            $idrincianindikatorro = $dri->id;
            $target = $dri->target;

            //dapatkan realisasi prosentase dari masing masing realisasi
            $prosentase = DB::table('realisasirincianindikatorro')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->where('idrincianindikatorro','=',$idrincianindikatorro)
                ->value('prosentase');

            $porsiprosentase = ($target/$targetindikatorro) * $prosentase;
            $prosentaseindikatorro = $prosentaseindikatorro + $porsiprosentase;
        }
        return $prosentaseindikatorro;
    }

    function dapatkanstatuspelaksanaanterbanyak($idindikatorro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasirincianindikatorro')
            ->select(['statuspelaksanaan',DB::raw('count(statuspelaksanaan) as statusterbanyak')])
            ->where('idindikatorro','=',$idindikatorro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('statusterbanyak','desc')
            ->value('statusterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkankategoripermasalahanterbanyak($idindikatorro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasirincianindikatorro')
            ->select(['kategoripermasalahan',DB::raw('count(kategoripermasalahan) as statusterbanyak')])
            ->where('idindikatorro','=',$idindikatorro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('statusterbanyak','desc')
            ->value('statusterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkanuraianoutputdihasilkan($idindikatorro, $tahunanggaran, $idbulan){
        $uraianoutputdihasilkan = DB::table('realisasirincianindikatorro')
            ->select([DB::raw('group_concat(uraianoutputdihasilkan) as uraianoutputdihasilkan')])
            ->where('idindikatorro','=',$idindikatorro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('uraianoutputdihasilkan');
        return $uraianoutputdihasilkan;
    }

    function dapatkanketerangan($idindikatorro, $tahunanggaran, $idbulan){
        $uraianoutputdihasilkan = DB::table('realisasirincianindikatorro')
            ->select([DB::raw('group_concat(keterangan) as keterangan')])
            ->where('idindikatorro','=',$idindikatorro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('keterangan');
        return $uraianoutputdihasilkan;
    }

}
