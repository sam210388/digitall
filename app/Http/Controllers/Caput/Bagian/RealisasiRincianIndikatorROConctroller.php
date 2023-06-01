<?php

namespace App\Http\Controllers\Caput\Bagian;

use App\Http\Controllers\Controller;
use App\Libraries\FilterDataUser;
use App\Models\Caput\Bagian\RealisasiRincianIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiRincianIndikatorROConctroller extends Controller
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

        return view('Caput.Bagian.realisasirincianindikatorro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan,
            //"data" => $data

        ]);

    }

    public function getdatarincianindikatorro(Request $request){
        $nilaibulan = $request->get('nilaibulan');
        $bulan = array(
            'nilaibulan' => $nilaibulan
        );
        $idrincianindikator = $request->get('idrincianindikatorro');
        $dataperiodesebelumnya = array();

        if ($nilaibulan == 1){
            $datarealisasisebelumnya = array(
                'jumlahsdperiodelalu' => 0,
                'prosentasesdperiodelalu' => 0.00
            );
            $dataperiodesebelumnya = array_merge($dataperiodesebelumnya,$datarealisasisebelumnya);
        }else{
            //dapatkan realisasi sebelumnya
            $datarealisasisebelumnya = DB::table('realisasirincianindikatorro')
                ->where('idrincianindikatorro','=',$idrincianindikator)
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
        $data = DB::table('rincianindikatorro as a')
            ->select(['a.targetpengisian as targetpengisian','a.volperbulan as volperbulan','a.infoproses as infoproses',
                'a.keterangan as keterangan','a.id as idrincianindikatorro','a.idindikatorro as idindikatorro','a.target as target'])
            ->where('a.id','=',$idrincianindikator)
            ->get()->toArray();
        $data = array_merge($data, $bulan);
        $data = array_merge($data,$dataperiodesebelumnya);
        return response()->json($data);
    }


    public function getdatarealisasi(Request $request, $idbulan)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        $iduser = Auth::id();
        $role = DB::table('role_users')->where('iduser','=',$iduser)->pluck('idrole')->toArray();
        $idbagian = Auth::user()->idbagian;
        $idbiro = Auth::user()->idbiro;
        if ($request->ajax()) {
            $data = DB::table('rincianindikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen,".",a.kodesubkomponen," | ",
                    a.uraianrincianindikatorro) as rincianindikatorro'), 'a.target as target','a.status as status',
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
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->groupBy('a.id');

            if ($idbagian == 0){
                $data->where('a.idbiro','=',$idbiro);
                $data->whereNull('a.idbagian');
            }else{
                $data->where('a.idbagian','=',$idbagian);
            }
            $data = $data->get(['indikatorro', 'rincianindikatorro', 'target', 'jumlah', 'jumlahsdperiodeini', 'prosentase',
                'prosentasesdperiodeini', 'statuspelaksanaan', 'kategoripermasalahan', 'uraianoutputdihasilkan',
                'keterangan', 'file', 'statusrealisasi']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->idrealisasi != null and $row->statusrealisasi != 3) {
                        $id = $row->idrealisasi."/".$row->idrincianindikatorro;
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
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->idrincianindikatorro . '" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
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

    public function simpanrealisasirincian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'tanggallapor' => 'required',
            'jumlah' => 'required|numeric',
            'jumlahsdperiodeini' => 'required|numeric',
            'prosentase' => 'required|between:0,100.00',
            'prosentasesdperiodeini' => 'required|between:0,100.00',
            'statuspelaksanaan' => 'required',
            'kategoripermasalahan' => 'required',
            'uraianoutputdihasilkan' => 'required',
            'keterangan' => 'required'
        ]);

        $tanggallapor = date_create($request->get('tanggallapor'));
        $tanggallapor = date_format($tanggallapor,'Y-m-d');
        $periode = $request->get('nilaibulan');
        $jumlah = $request->get('jumlah');
        $jumlahsdperiodeini = $request->get('jumlahsdperiodeini');
        $prosentase = $request->get('prosentase');
        $prosentasesdperiodeini = $request->get('prosentasesdperiodeini');
        $statuspelaksanaan = $request->get('statuspelaksanaan');
        $kategoripermasalahan = $request->get('kategoripermasalahan');
        $uraianoutputdihasilkan = $request->get('uraianoutputdihasilkan');
        $keterangan = $request->get('keterangan');
        $idindikatorro = $request->get('idindikatorro');
        $idrincianindikatorro = $request->get('idrincianindikatorro');

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'rincianindikatoroutput','public');
        }

        RealisasiRincianIndikatorROModel::create([
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
            'idindikatorro' => $idindikatorro,
            'idrincianindikatorro' => $idrincianindikatorro

        ]);

        //update status rincian menjadi selesai
        if ($prosentasesdperiodeini == 100){
            $updatestatus = array(
                'status' => "Selesai",
                'periodeselesai' => date('n',$tanggallapor)
            );

            DB::table('rincianindikatorro')->where('id','=',$idrincianindikatorro)->update($updatestatus);
        }

        return response()->json(['status'=>'berhasil']);
    }

    public function editrealisasirincian(Request $request){
        $idrealisasi = $request->get('idrealisasi');
        $nilaibulan = $request->get('nilaibulan');
        $idrincianindikatorro = $request->get('idrincianindikatorro');

        //dapatkan data realisasi saat ini
        $datarealisasisaatini = DB::table('realisasirincianindikatorro as a')
            ->select(['a.tahunanggaran as tahunanggaran','a.periode as periode','a.tanggallapor as tanggallapor','a.jumlah as jumlah','a.jumlahsdperiodeini as jumlahsdperiodeini',
                'a.prosentase as prosentase','a.prosentasesdperiodeini as prosentasesdperiodeini','a.statuspelaksanaan as statuspelaksanaan','a.kategoripermasalahan as kategoripermasalahan',
                'a.uraianoutputdihasilkan as uraianoutputdihasilkan','a.keterangan as keterangan','a.status as status','a.idindikatorro as indikatorro','a.idrincianindikatorro as idrincianindikatorro',
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
            $datarealisasisebelumnya = DB::table('realisasirincianindikatorro')
                ->where('idrincianindikatorro','=',$idrincianindikatorro)
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
        $datarincianindikatorro = DB::table('rincianindikatorro')->where('id','=',$idrincianindikatorro)->get()->toArray();

        //gabung data
        $dataresponse = array_merge($datarealisasisaatini->toArray(), $datarincianindikatorro);
        $dataresponse = array_merge($dataresponse, $datatambahan);
        return response()->json($dataresponse);

    }

    public function updaterealisasirincian(Request $request, $id){
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'tanggallapor' => 'required',
            'jumlah' => 'required|numeric',
            'jumlahsdperiodeini' => 'required|numeric',
            'prosentase' => 'required|between:0,100.00',
            'prosentasesdperiodeini' => 'required|between:0,100.00',
            'statuspelaksanaan' => 'required',
            'kategoripermasalahan' => 'required',
            'uraianoutputdihasilkan' => 'required',
            'keterangan' => 'required'
        ]);

        $tanggallapor = date_create($request->get('tanggallapor'));
        $tanggallapor = date_format($tanggallapor,'Y-m-d');
        $periode = $request->get('nilaibulan');
        $jumlah = $request->get('jumlah');
        $jumlahsdperiodeini = $request->get('jumlahsdperiodeini');
        $prosentase = $request->get('prosentase');
        $prosentasesdperiodeini = $request->get('prosentasesdperiodeini');
        $statuspelaksanaan = $request->get('statuspelaksanaan');
        $kategoripermasalahan = $request->get('kategoripermasalahan');
        $uraianoutputdihasilkan = $request->get('uraianoutputdihasilkan');
        $keterangan = $request->get('keterangan');
        $idindikatorro = $request->get('idindikatorro');
        $idrincianindikatorro = $request->get('idrincianindikatorro');

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'rincianindikatoroutput','public');
        }


        DB::table('realisasirincianindikatorro')->where('id','=',$id)->update([
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
            'idindikatorro' => $idindikatorro,
            'idrincianindikatorro' => $idrincianindikatorro
        ]);

        if ($prosentasesdperiodeini == 100){
            $updatestatus = array(
                'status' => "Selesai",
                'periodeselesai' => date('n',$tanggallapor)
            );
            DB::table('rincianindikatorro')->where('id','=',$idrincianindikatorro)->update($updatestatus);
        }
        return response()->json(['status'=>'berhasil']);
    }

    public function deleterealisasi($idrealisasi){
        DB::table('realisasirincianindikatorro')->delete($idrealisasi);
        return response()->json(['status'=>'berhasil']);
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
