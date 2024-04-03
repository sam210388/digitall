<?php

namespace App\Http\Controllers\Caput\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Caput\Bagian\RealisasiIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiIndikatorROBagianConctroller extends Controller
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

        return view('Caput.Bagian.realisasiindikatorro',[
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
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data = DB::table('indikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen," | ",
                    a.uraianindikatorro) as indikatorro'), 'a.target as target','a.status as status','a.idkro as idkro','a.idro as idro','e.uraianro as uraianro',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan',
                    'f.uraianstatus as statusrealisasi', 'e.uraianro as ro',
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
                ->leftJoin('statusrealisasi as f','b.status','=','f.id')
                ->where('a.idbagian', '=', $idbagian)
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->groupBy('a.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->idrealisasi != null and $row->statusrealisasi != 3) {
                        $id = $row->idrealisasi."/".$row->idindikatorro;
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editrealisasi">Edit</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idrealisasi . '" data-original-title="Delete" class="btn btn-danger btn-sm deleterealisasi">Delete</a>';
                        return $btn;
                    } else if ($row->idrealisasi != null and $row->statusrealisasi == 3){
                        $btn = '';
                        return $btn;
                    }else if ($row->status == 'Selesai'){
                        $btn = '<a data-original-title="Edit" class="edit btn btn-success btn-sm targetkinerja">Target Tercapai</a>';
                        return $btn;
                    }else{
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idindikatorro . '" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
                        return $btn;
                    }
                })
                ->rawColumns(['action','statusrealisasi'])
                ->make(true);
        }
    }

    public function getdataindikatorro(Request $request){
        $nilaibulan = $request->get('nilaibulan');
        $bulan = array(
            'nilaibulan' => $nilaibulan
        );
        $idindikator = $request->get('idindikatorro');
        $dataperiodesebelumnya = array();

        if ($nilaibulan == 1){
            $datarealisasisebelumnya = array(
                'jumlahsdperiodelalu' => 0,
                'prosentasesdperiodelalu' => 0.00
            );
            $dataperiodesebelumnya = array_merge($dataperiodesebelumnya,$datarealisasisebelumnya);
        }else{
            //dapatkan realisasi sebelumnya
            $datarealisasisebelumnya = DB::table('realisasiindikatorro')
                ->where('idindikatorro','=',$idindikator)
                ->where('periode','=',$nilaibulan-1)
                ->get(['jumlahsdperiodeini','prosentasesdperiodeini']);
            foreach ($datarealisasisebelumnya as $drs){
                $datasebelumnya = array(
                    'jumlahsdperiodelalu' => $drs->jumlahsdperiodeini,
                    'prosentasesdperiodelalu' => $drs->prosentasesdperiodeini
                );
                $dataperiodesebelumnya = array_merge($dataperiodesebelumnya,$datasebelumnya);
            }
        }
        $targetbulan = 'target'.$nilaibulan;
        $data = DB::table('indikatorro as a')
            ->select(['target',$targetbulan,'id','idro','idkro'])
            ->where('a.id','=',$idindikator)
            ->get()->toArray();
        $data = array_merge($data, $bulan);
        $data = array_merge($data,$dataperiodesebelumnya);
        return response()->json($data);
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

    /* function rekaprealisasiindikatorro(Request $request){
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
        //ubah status ke sudah divalidasi
        DB::table('realisasirincianindikatorro')
            ->where('idindikatorro','=',$idindikatorro)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->update(['status' => 3]);


        if ($prosentasesdperiodeini == 100){
            //jadikan indikator RO nya selesai
            $dataupdate = array(
                'status' => "Selesai",
                'periodeselesai' => $idbulan
            );
            DB::table('indikatorro')->where('idindikatorro','=',$idindikatorro)->update($dataupdate);
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
    */

    public function simpanrealisasirincian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'jumlah' => 'required|numeric',
            'jumlahsdperiodeini' => 'required|numeric',
            'prosentase' => 'required|between:0,100.00',
            'prosentasesdperiodeini' => 'required|between:0,100.00',
            'statuspelaksanaan' => 'required',
            'kategoripermasalahan' => 'required',
            'uraianoutputdihasilkan' => 'required',
            'keterangan' => 'required'
        ]);

        //$tanggallapor = date_create($request->get('tanggallapor'));
        //$tanggallapor = date_format($tanggallapor,'Y-m-d');
        $tanggallapor = now();
        $tanggallapor = date_format($tanggallapor,'Y-m-d');
        $periode = $request->get('nilaibulan');
        $jumlah = intval($request->get('jumlah'));
        $target = intval($request->get('target'));
        $targetbulan = intval($request->get('targetbulan'));
        $jumlahsdperiodeini = intval($request->get('jumlahsdperiodeini'));
        $prosentase = floatval($request->get('prosentase'));
        $prosentasesdperiodeini = floatval($request->get('prosentasesdperiodeini'));
        $statuspelaksanaan = $request->get('statuspelaksanaan');
        $kategoripermasalahan = $request->get('kategoripermasalahan');
        $uraianoutputdihasilkan = $request->get('uraianoutputdihasilkan');
        $keterangan = $request->get('keterangan');
        $idindikatorro = $request->get('idindikatorro');
        $idro = $request->get('idro');
        $idkro = $request->get('idkro');

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'realisasiindikatorro','public');
        }else{
            $file = null;
        }

        RealisasiIndikatorROModel::create([
            'target' => $target,
            'targetbulan' => $targetbulan,
            'tahunanggaran' => $tahunanggaran,
            'tanggallapor' => $tanggallapor,
            'periode' => $periode,
            'jumlah' => $jumlah,
            'jumlahsdperiodeini' => $jumlahsdperiodeini,
            'prosentase' => $prosentase,
            'prosentasesdperiodeini' => $prosentasesdperiodeini,
            'statuspelaksanaan' => $statuspelaksanaan,
            'kategoripermasalahan' => $kategoripermasalahan,
            'uraianoutputdihasilkan' => $uraianoutputdihasilkan,
            'keterangan' => $keterangan,
            'status' => 1,
            'file' => $file,
            'idindikatorro' => $idindikatorro,
            'idro' => $idro,
            'idkro' => $idkro

        ]);

        //update status rincian menjadi selesai
        if ($prosentasesdperiodeini == 100){
            $updatestatus = array(
                'status' => "Selesai",
                'periodeselesai' => date('n',$tanggallapor)
            );

            DB::table('indikatorro')->where('id','=',$idindikatorro)->update($updatestatus);
        }

        return response()->json(['status'=>'berhasil']);
    }

    public function editrealisasiindikatorro(Request $request){
        $idrealisasi = $request->get('idrealisasi');
        $nilaibulan = $request->get('nilaibulan');
        $idindikatorro = $request->get('idindikatorro');

        //dapatkan data realisasi saat ini
        $datarealisasisaatini = DB::table('realisasiindikatorro as a')
            ->select(['a.tahunanggaran as tahunanggaran','a.periode as periode','a.tanggallapor as tanggallapor','a.targetbulan as targetbulan','a.target as target','a.jumlah as jumlah','a.jumlahsdperiodeini as jumlahsdperiodeini',
                'a.prosentase as prosentase','a.prosentasesdperiodeini as prosentasesdperiodeini','a.statuspelaksanaan as statuspelaksanaan','a.kategoripermasalahan as kategoripermasalahan',
                'a.uraianoutputdihasilkan as uraianoutputdihasilkan','a.keterangan as keterangan','a.status as status','a.idindikatorro as indikatorro','a.idro as idro','a.idkro as idkro',
                'a.file as file','a.id as idrealisasi'])
            ->where('id','=',$idrealisasi)->get();

        //dapatkan realisasi sebelumnya
        $datatambahan = array();
        if ($nilaibulan == 1){
            $datatambahan = array(
                'jumlahsdperiodelalu' => 0,
                'prosentasesdperiodelalu' => 0.00
            );
        } else{
            $datarealisasisebelumnya = DB::table('realisasiindikatorro')
                ->where('idindikatorro','=',$idindikatorro)
                ->where('periode','=',$nilaibulan-1)
                ->get();
            foreach ($datarealisasisebelumnya as $d){
                $jumlahsdperiodelalu = $d->jumlahsdperiodeini;
                $prosentasesdperiodelalu = $d->prosentasesdperiodeini;
                $datatambahanbaru = array(
                    'jumlahsdperiodelalu' => $jumlahsdperiodelalu,
                    'prosentasesdperiodelalu' => $prosentasesdperiodelalu
                );
                $datatambahan = array_merge($datatambahan,$datatambahanbaru);
            }
        }
        //dapatkan data rincian indikator
        $dataindikatorro = DB::table('indikatorro')->where('id','=',$idindikatorro)->get()->toArray();

        //gabung data
        $dataresponse = array_merge($datarealisasisaatini->toArray(), $dataindikatorro);
        $dataresponse = array_merge($dataresponse, $datatambahan);
        return response()->json($dataresponse);

    }

    public function updaterealisasirincian(Request $request, $id){
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'jumlah' => 'required|numeric',
            'jumlahsdperiodeini' => 'required|numeric',
            'prosentase' => 'required|between:0,100.00',
            'prosentasesdperiodeini' => 'required|between:0,100.00',
            'statuspelaksanaan' => 'required',
            'kategoripermasalahan' => 'required',
            'uraianoutputdihasilkan' => 'required',
            'keterangan' => 'required'
        ]);

        //$tanggallapor = date_create($request->get('tanggallapor'));
        //$tanggallapor = date_format($tanggallapor,'Y-m-d');
        $tanggallapor = now();
        $tanggallapor = date_format($tanggallapor,'Y-m-d');
        $target = intval($request->get('target'));
        $targetbulan = intval($request->get('targetbulan'));
        $periode = $request->get('nilaibulan');
        $jumlah = intval($request->get('jumlah'));
        $jumlahsdperiodeini = intval($request->get('jumlahsdperiodeini'));
        $prosentase = floatval($request->get('prosentase'));
        $prosentasesdperiodeini = floatval($request->get('prosentasesdperiodeini'));
        $statuspelaksanaan = $request->get('statuspelaksanaan');
        $kategoripermasalahan = $request->get('kategoripermasalahan');
        $uraianoutputdihasilkan = $request->get('uraianoutputdihasilkan');
        $keterangan = $request->get('keterangan');
        $idindikatorro = $request->get('idindikatorro');
        $idro = $request->get('idro');
        $idkro = $request->get('idkro');

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'rincianindikatoroutput','public');
        }else{
            $file = null;
        }


        DB::table('realisasiindikatorro')->where('id','=',$id)->update([
            'target' => $target,
            'targetbulan' => $targetbulan,
            'tahunanggaran' => $tahunanggaran,
            'tanggallapor' => $tanggallapor,
            'periode' => $periode,
            'jumlah' => $jumlah,
            'jumlahsdperiodeini' => $jumlahsdperiodeini,
            'prosentase' => $prosentase,
            'prosentasesdperiodeini' => $prosentasesdperiodeini,
            'statuspelaksanaan' => $statuspelaksanaan,
            'kategoripermasalahan' => $kategoripermasalahan,
            'uraianoutputdihasilkan' => $uraianoutputdihasilkan,
            'keterangan' => $keterangan,
            'status' => 1,
            'file' => $file,
            'idindikatorro' => $idindikatorro,
            'idro' => $idro,
            'idkro' => $idkro
        ]);

        if ($prosentasesdperiodeini == 100){
            $updatestatus = array(
                'status' => "Selesai",
                'periodeselesai' => date('n',$tanggallapor)
            );
            DB::table('indikatorro')->where('id','=',$idindikatorro)->update($updatestatus);
        }
        return response()->json(['status'=>'berhasil']);
    }

    public function deleterealisasi($idrealisasi){
        DB::table('realisasiindikatorro')->delete($idrealisasi);
        return response()->json(['status'=>'berhasil']);
    }

}
