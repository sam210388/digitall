<?php

namespace App\Http\Controllers\Caput\Biro;

use App\Http\Controllers\Controller;
use App\Models\Caput\Biro\RealisasiKROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiKROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasikro(){
        $judul = 'List Realisasi KRO';
        $databulan = DB::table('bulan')->get();
        $datastatuspelaksanaan = DB::table('statuspelaksanaan')->get();
        $datakategoripermasalahan = DB::table('kategoripermasalahan')->get();

        return view('Caput.Biro.realisasikro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan,
            //"data" => $data

        ]);

    }

    public function getdatarealisasikro(Request $request, $idbulan)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data = DB::table('kro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput," | ",a.uraiankro) as kro'), 'a.target as target',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan','b.status as statusrealisasi',
                    'a.id as idkro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasikro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idkro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->where('a.idbiro', '=', $idbiro)
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->groupBy('a.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idkro . '" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
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

    public function cekjadwallapor($idkro, $idbulan){
        $kondisilapor = "";
        $tahunanggaran = session('tahunanggaran');
        //cekrealisasisebelumnya
        if ($idbulan == 1){
            $laporsebelumnya = true;
        }else{
            $adarealisasi = DB::table('realisasikro')
                ->where('idkro','=',$idkro)
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

    function rekaprealisasikro(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idkro = $request->get('idkro');
        $idbulan = $request->get('nilaibulan');
        $tanggallapor = date('Y-m-d');

        //dapatkan target dan jenis kro
        $targetkro = "";
        $jenisindikator = "";

        $datakro = DB::table('kro')
            ->where('id','=',$idkro)
            ->get();
        foreach ($datakro as $dir){
            $targetkro = $dir->target;
            $jenisindikator = $dir->jenisindikator;
        }

        //dapatkan data periode sebelumnya
        $jumlahsdperiodesebelumnya = "";
        $prosentasesdperiodesebelumnya = "";
        if ($idbulan == 1){
            $jumlahsdperiodesebelumnya = 0;
            $prosentasesdperiodesebelumnya = 0.00;
        }else{
            $datarealisasisebelumnya = DB::table('realisasikro')
                ->where('idkro','=',$idkro)
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
            $jumlah = DB::table('realisasiro')
                ->select([DB::raw('sum(jumlah) as jumlah')])
                ->where('idkro','=',$idkro)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->value('jumlah');
            $jumlahsdperiodeini = $jumlahsdperiodesebelumnya+$jumlah;
            $prosentase = $this->rekapprosentasekro($idkro, $tahunanggaran, $idbulan, $targetkro);
            $prosentase = round($prosentase,2);
            $prosentasesdperiodeini = $prosentasesdperiodesebelumnya+$prosentase;

        }

        //dapatkan statusterbanyak
        $statuspelaksanaan = $this->dapatkanstatuspelaksanaanterbanyak($idkro, $tahunanggaran, $idbulan);

        //dapatkan kategori permasalahan terbanyak
        $kategoripermasalahan = $this->dapatkankategoripermasalahanterbanyak($idkro, $tahunanggaran, $idbulan);

        //dapatkan uraian output dihasilkan
        $uraianoutputdihasilkan = $this->dapatkanuraianoutputdihasilkan($idkro, $tahunanggaran, $idbulan);

        //dapatkan keterangan
        $keterangan = $this->dapatkanketerangan($idkro, $tahunanggaran, $idbulan);

        $data = array(
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
        $adarealisasi = DB::table('realisasikro')
            ->where('idkro','=',$idkro)
            ->where('periode','=',$idbulan)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->count();
        if ($adarealisasi == 0){
            DB::table('realisasikro')->insert($data);
        }else{
            DB::table('realisasikro')
                ->where('idkro','=',$idkro)
                ->where('periode','=',$idbulan)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->delete();
            DB::table('realisasikro')->insert($data);
        }
        return response()->json(['status'=> 'Rekap Realisasi Berhasil']);

    }

    function rekapprosentasekro($idkro, $tahunanggaran, $idbulan, $targetkro){
        $prosentasekro = 0;
        $dataro = DB::table('ro')
            ->where('idkro','=',$idkro)
            ->get();
        foreach ($dataro as $dri){
            $idro = $dri->id;
            $target = $dri->target;

            //dapatkan realisasi prosentase dari masing masing realisasi
            $prosentase = DB::table('realisasiro')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->where('idro','=',$idro)
                ->value('prosentase');

            $porsiprosentase = ($target/$targetkro) * $prosentase;
            $prosentasekro = $prosentasekro + $porsiprosentase;
        }
        return $prosentasekro;
    }

    function dapatkanstatuspelaksanaanterbanyak($idkro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasiro')
            ->select(['statuspelaksanaan',DB::raw('count(statuspelaksanaan) as statusterbanyak')])
            ->where('idkro','=',$idkro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('statusterbanyak','desc')
            ->value('statusterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkankategoripermasalahanterbanyak($idkro, $tahunanggaran, $idbulan){
        $statuspelaksanaan = DB::table('realisasiro')
            ->select(['kategoripermasalahan',DB::raw('count(kategoripermasalahan) as kategoriterbanyak')])
            ->where('idkro','=',$idkro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->groupBy('statuspelaksanaan')
            ->orderBy('kategoriterbanyak','desc')
            ->value('kategoriterbanyak');
        return $statuspelaksanaan;
    }

    function dapatkanuraianoutputdihasilkan($idkro, $tahunanggaran, $idbulan){
        $uraianoutputdihasilkan = DB::table('realisasiro')
            ->select([DB::raw('group_concat(uraianoutputdihasilkan) as uraianoutputdihasilkan')])
            ->where('idkro','=',$idkro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('uraianoutputdihasilkan');
        return $uraianoutputdihasilkan;
    }

    function dapatkanketerangan($idkro, $tahunanggaran, $idbulan){
        $keterangan = DB::table('realisasiro')
            ->select([DB::raw('group_concat(keterangan) as keterangan')])
            ->where('idkro','=',$idkro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->value('keterangan');
        return $keterangan;
    }

}
