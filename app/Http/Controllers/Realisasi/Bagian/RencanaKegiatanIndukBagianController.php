<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Exports\ExportRencanaPenarikanBagian;
use App\Http\Controllers\Controller;
use App\Models\Realisasi\Bagian\MonitoringRencanaKegiatanBagianModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanDetilModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RencanaKegiatanIndukBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tampilmonitoring(){
        $judul = 'Monitoring Rencana Penarikan Bagian';
        return view('Realisasi.Bagian.rencanakegiatanbagian',[
            "judul"=>$judul,
        ]);
    }

    public function getdatamonitoring()
    {
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $model = MonitoringRencanaKegiatanBagianModel::with('bagianrelation')
            ->where('idbagian','=',$idbagian)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->select(['rencanakegiatan.*']);
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (MonitoringRencanaKegiatanBagianModel $id) {
                return $id->idbagian ? $id->bagianrelation->uraianbagian:"";
            })
            ->addColumn('sisadialokasikan', function ($id) {
                $paguanggaran = $id->paguanggaran;
                $totalrencana = $id->totalrencana;
                $sisadialokasikan = $paguanggaran - $totalrencana;
                return $sisadialokasikan;
            })

            ->rawColumns(['action','bagian','sisadialokasikan'])
            ->toJson();
    }

    public function updatetabelmonitoring($pengenal, $bulanpencairan){
        $nilairencanabulan = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as nilairencana')])
            ->where('pengenal','=',$pengenal)
            ->where('bulanpencairan','=',$bulanpencairan)
            ->value('nilairencana');

        //update total rencana
        $totalrencana = DB::table('rencanakegiatan')
            ->select([DB::raw('sum(pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12) as totalrencana')])
            ->where('pengenal','=',$pengenal)
            ->value('totalrencana');
        $totalrencanaakhir = $totalrencana+$nilairencanabulan;

        $datapengenal = DB::table('laporanrealisasianggaranbac')
            ->where('pengenal','=',$pengenal)
            ->get();
        $kodesatker = "";
        $idbiro = "";
        $idbagian = "";
        $tahunanggaran = "";
        $paguanggaran = 0;

        foreach ($datapengenal as $item){
            $kodesatker = $item->kodesatker;
            $idbagian = $item->idbagian;
            $idbiro = $item->idbiro;
            $tahunanggaran = $item->tahunanggaran;
            $paguanggaran = $item->paguanggaran;
        }

        //update atau cetak datanya
        DB::table('rencanakegiatan')->updateOrInsert([
            'pengenal' => $pengenal
        ],[
            'pok'.$bulanpencairan => $nilairencanabulan,
            'tahunanggaran' => $tahunanggaran,
            'paguanggaran' => $paguanggaran,
            'kdsatker' => $kodesatker,
            'idbagian' => $idbagian,
            'idbiro' => $idbiro,
            'totalrencana' => $totalrencanaakhir
        ]);
    }

    function exportrencanapenarikanbagian(){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRencanaPenarikanBagian($tahunanggaran, $idbagian),'MonitoringRencanaPenarikanBagian.xlsx');
    }

    function rekaprealisasiberjalan(){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $bulan = date('n');
        //Excel::download(new UsersExport, 'users.xlsx');
        //return Excel::download(new ExportRencanaPenarikanBagian($tahunanggaran, $idbagian),'MonitoringRencanaPenarikanBagian.xlsx');
        for($i=1; $i<=$bulan;$i++){
            $datalaporanrealisasibac = DB::table('laporanrealisasianggaranbac')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->get();
            $namafield = 'r';
            foreach ($datalaporanrealisasibac as $data){
                $pengenal = $data->pengenal;
                $kodesatker = $data->kodesatker;
                $idbagian = $data->idbagian;
                $idbiro = $data->idbiro;
                $tahunanggaran = $data->tahunanggaran;
                $paguanggaran = $data->paguanggaran;
                $realisasibulani = $data->{"r$i"};
                $totalrencana = DB::table('rencanakegiatan')
                    ->select([DB::raw('sum(pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12) as totalrencana')])
                    ->where('pengenal','=',$pengenal)
                    ->value('totalrencana');

                //update atau cetak datanya
                DB::table('rencanakegiatan')->updateOrInsert([
                    'pengenal' => $pengenal
                ],[

                    'tahunanggaran' => $tahunanggaran,
                    'paguanggaran' => $paguanggaran,
                    'kdsatker' => $kodesatker,
                    'idbagian' => $idbagian,
                    'idbiro' => $idbiro,
                    'pok'.$i => $realisasibulani,
                    'totalrencana' => $totalrencana
                ]);

            }
        }

        return redirect()->to('monitoringrencanakegiatan')->with('status','berhasil');


    }

    public function tampildetil($idrencanakegiatan)
    {
        $judul = 'Data Detil Rencana Kegiatan';
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $kdsatker = DB::table('rencanakegiataninduk')
            ->where('id','=',$idrencanakegiatan)
            ->value('kdsatker');
        $datapengenal = DB::table('laporanrealisasianggaranbac')
            ->where('idbagian','=',$idbagian)
            ->where('kodesatker','=',$kdsatker)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        $bulanpencairan = DB::table('rencanakegiataninduk')
            ->where('id','=',$idrencanakegiatan)
            ->value('bulanpencairan');

        return view('Realisasi.Bagian.rencanakegiatanbagiandetil',[
            "judul"=>$judul,
            "datapengenal" => $datapengenal,
            "idrencanakegiatan" => $idrencanakegiatan,
            "bulanpencairan" => $bulanpencairan,
            "kdsatker" => $kdsatker
        ]);
    }

    public function getdetilrencanakegiatanbagian()
    {
        $idrencanakegiatan = $_GET['idrencanakegiatan'];
        $model = RencanaKegiatanDetilModel::with('rencanakegiatanrelation')
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->select('rencanakegiatandetail.*');
        return Datatables::eloquent($model)
            ->addColumn('action', function($row){

                $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editdetil">Edit</a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedetil">Delete</a>';
                return $btn;
            })
            ->addColumn('uraianrencanakegiatan', function (RencanaKegiatanDetilModel $id) {
                return $id->idrencanakegiatan ? $id->rencanakegiatanrelation->uraiankegiatan:"";
            })
            ->rawColumns(['action','uraianrencanakegiatan'])
            ->toJson();
    }

    public function index()
    {
        $judul = 'Data Rencana Kegiatan';
        return view('Realisasi.Bagian.rencanakegiataninduk',[
            "judul"=>$judul,
        ]);
    }

    public function getdatarencanakegiatanbagian()
    {
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $model = RencanaKegiatanModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->select('rencanakegiataninduk.*');
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (RencanaKegiatanModel $id) {
                return $id->idbagian ? $id->bagianpengajuanrelation->uraianbagian:"";
            })
            ->addColumn('action', function($row){
                if ($row->statusubah == "Open" and $row->statusrencana == "Terjadwal" ){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editrencana">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterencana">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm setterlaksana">Terlaksana</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-primary btn-sm detilrencana">Detil</a>';
                }else if($row->statusubah == "Open" and $row->statusrencana == "Terlaksana" ){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editrencana">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterencana">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm setterjadwal">Terjadwal</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-primary btn-sm detilrencana">Detil</a>';
                } else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }

    public function setrencanaterlaksana($idrencanakegiatan){
        //ubah status rencana kegiatan induk menjadi terlaksana
        DB::table('rencanakegiataninduk')->where('id','=',$idrencanakegiatan)->update([
            'statusrencana' => 'Terlaksana'
        ]);

        //ubah status detil rencana kegiatan menjadi terlaksana
        DB::table('rencanakegiatandetail')->where('idrencanakegiatan','=',$idrencanakegiatan)->update([
            'statusrencana' => 'Terlaksana'
        ]);

        return response()->json(['status'=>'berhasil']);
    }

    public function setrencanaterjadwal($idrencanakegiatan){
        //ubah status rencana kegiatan induk menjadi terlaksana
        DB::table('rencanakegiataninduk')->where('id','=',$idrencanakegiatan)->update([
            'statusrencana' => 'Terjadwal'
        ]);

        //ubah status detil rencana kegiatan menjadi terlaksana
        DB::table('rencanakegiatandetail')->where('idrencanakegiatan','=',$idrencanakegiatan)->update([
            'statusrencana' => 'Terjadwal'
        ]);

        return response()->json(['status'=>'berhasil']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'uraiankegiatan' => 'required',
            'bulanpelaksanaan' => 'required',
            'bulanpencairan' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        $idbagian = Auth::user()->idbagian;
        $idbiro = Auth::user()->idbiro;
        $uraiankegiatan = $request->get('uraiankegiatan');
        $totalrencana = intval($request->get('totalrencana'));
        $bulanpelaksanaan = $request->get('bulanpelaksanaan');
        $bulanpencairan = $request->get('bulanpencairan');
        //$nilairealisasi = intval($request->get('nilairealisasi'));


        RencanaKegiatanModel::create(
            [
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagian,
                'idbiro' => $idbiro,
                'uraiankegiatan' => $uraiankegiatan,
                'bulanpelaksanaan' => $bulanpelaksanaan,
                'bulanpencairan' => $bulanpencairan,
                'totalrencana' => $totalrencana,
                'statusubah' => "Open",
                'created_at' => now(),
                'created_by' => Auth::user()->id,
                'updated_at' => now(),
                'updated_by' => Auth::user()->id,
                'statusrencana' => "Terjadwal"
            ]);
        return response()->json(['status'=>'berhasil']);
    }

    public function edit($id){
        $menu = RencanaKegiatanModel::find($id);
        return response()->json($menu);
    }


    public function update(Request $request, $id)
    {
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'uraiankegiatan' => 'required',
            'bulanpelaksanaan' => 'required',
            'bulanpencairan' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        $idbagian = Auth::user()->idbagian;
        $idbiro = Auth::user()->idbiro;
        $uraiankegiatan = $request->get('uraiankegiatan');
        $totalrencana = intval($request->get('totalrencana'));
        $bulanpelaksanaan = $request->get('bulanpelaksanaan');
        $bulanpencairan = $request->get('bulanpencairan');

        RencanaKegiatanModel::where([
            'id' => $id
        ])->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagian,
                'idbiro' => $idbiro,
                'uraiankegiatan' => $uraiankegiatan,
                'bulanpelaksanaan' => $bulanpelaksanaan,
                'bulanpencairan' => $bulanpencairan,
                'totalrencana' => $totalrencana,
                'statusubah' => "Open",
                'created_at' => now(),
                'created_by' => Auth::user()->id,
                'updated_at' => now(),
                'updated_by' => Auth::user()->id,
                'statusrencana' => "Terjadwal",
            ]);
        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete jg detilnya

        RencanaKegiatanModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }



    public function ambildatapengenal(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $kdsatker = $request->get('kdsatker');
        $data['pengenal'] = DB::table('laporanrealisasianggaranbac as a')
            ->select('a.pengenal as pengenal','a.paguanggaran as paguanggaran')
            ->where('a.kodesatker','=',$kdsatker)
            ->where('a.idbagian','=',$idbagian)
            ->where('a.tahunanggaran','=',$tahunanggaran)
            ->groupBy('a.pengenal')
            ->get(['pengenal','paguanggaran']);
        return response()->json($data);
    }

    public function ambildatapengenaldetil(Request $request)
    {
        $pengenal = $request->get('pengenal');
        $iddetilrencana = intval($request->get('iddetilrencana'));

        // Menggunakan toArray() untuk mengonversi hasil query menjadi array
        $query = DB::table('laporanrealisasianggaranbac as a')
            ->select('a.paguanggaran as paguanggaran', 'a.rsd12 as rsd12')
            ->where('a.pengenal', '=', $pengenal)
            ->groupBy('a.pengenal')
            ->get()
            ->toArray();

        $data['data'] = $query;

        if ($iddetilrencana >0){
            $nilairencana = DB::table('rencanakegiatandetail')
                ->select([DB::raw('sum(rupiah) as totalrencanasebelumnya')])
                ->where('pengenal','=',$pengenal)
                ->where('id','!=',$iddetilrencana)
                ->where('statusrencana','=','Terjadwal')
                ->get('totalrencanasebelumnya')
                ->toArray();

        }else{
            $nilairencana = DB::table('rencanakegiatandetail')
                ->select([DB::raw('sum(rupiah) as totalrencanasebelumnya')])
                ->where('pengenal','=',$pengenal)
                ->where('statusrencana','=','Terjadwal')
                ->get('totalrencanasebelumnya')
                ->toArray();
        }
        // Tidak perlu menyimpan hasil array_push dalam variabel $data
        array_push($data['data'], $nilairencana);

        // Mengembalikan response JSON yang berisi data
        return response()->json($data);
    }


    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
    }

    public function simpandetilrencana(Request $request)
    {
        $idrencanakegiatan = $request->get('idrencanakegiatan');
        $pengenal = $request->get('pengenal');
        $rupiah = ($request->get('nilairencana'));
        $rupiah = $this->formatulang($rupiah);

        $bulanpencairan = $request->get('bulanpencairandetil');
        //$nilairealisasi = intval($request->get('nilairealisasi'));


        RencanaKegiatanDetilModel::create(
            [
               'idrencanakegiatan' => $idrencanakegiatan,
                'bulanpencairan' => $bulanpencairan,
                'pengenal' => $pengenal,
                'rupiah' => $rupiah
            ]);

        //update nilai total rencana kegiatan
        //cari nilai total rencana kegiatan

        $nilaitotalrencana = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as nilairencana')])
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->value('nilairencana');

        DB::table('rencanakegiataninduk')->where('id','=',$idrencanakegiatan)->update([
            'totalrencana' => $nilaitotalrencana
        ]);

        //update nilai pengenal pada bulan tersebut pada tabel monitoring
        $this->updatetabelmonitoring($pengenal, $bulanpencairan);

        return response()->json(['status'=>'berhasil']);
    }



    public function editdetilrencana($id){
        //$menu = RencanaKegiatanDetilModel::find($id);
        $menu = DB::table('rencanakegiatandetail')->where('id','=',$id)->get()->toArray();
        return response()->json($menu);
    }


    public function updatedetilrencana(Request $request)
    {
        $iddetilrencana = $request->get('iddetilrencana');
        $idrencanakegiatan = $request->get('idrencanakegiatan');
        $pengenal = $request->get('pengenal');
        $rupiah = ($request->get('nilairencana'));
        $rupiah = $this->formatulang($rupiah);
        $bulanpencairan = $request->get('bulanpencairandetil');
        //$nilairealisasi = intval($request->get('nilairealisasi'));


        RencanaKegiatanDetilModel::where([
            'id' => $iddetilrencana
        ])->update(
            [
                'idrencanakegiatan' => $idrencanakegiatan,
                'bulanpencairan' => $bulanpencairan,
                'pengenal' => $pengenal,
                'rupiah' => $rupiah,
            ]);

        //update nilai total rencana kegiatan
        //cari nilai total rencana kegiatan

        $nilaitotalrencana = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as nilairencana')])
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->value('nilairencana');

        DB::table('rencanakegiataninduk')->where('id','=',$idrencanakegiatan)->update([
            'totalrencana' => $nilaitotalrencana
        ]);

        //update nilai pengenal pada bulan tersebut pada tabel monitoring
        $this->updatetabelmonitoring($pengenal, $bulanpencairan);

        return response()->json(['status'=>"berhasil"]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapusdetilrencana($id)
    {
        $bulanpencairan = DB::table('rencanakegiatandetail')->where('id','=', $id)->value('bulanpencairan');

        $pengenal = DB::table('rencanakegiatandetail')
            ->where('id','=',$id)
            ->value('pengenal');
        $idrencanakegiatan = DB::table('rencanakegiatandetail')
            ->where('id','=',$id)
            ->value('idrencanakegiatan');

        RencanaKegiatanDetilModel::find($id)->delete();

        //update total rencana
        $totalrencanapengenal = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as totalrencanapengenalawal')])
            ->where('pengenal','=',$pengenal)
            ->value('totalrencanapengenalawal');

        //update nilai total rencana kegiatan
        DB::table('rencanakegiataninduk')->where('id','=',$idrencanakegiatan)->update([
            'totalrencana' => $totalrencanapengenal
        ]);

        $this->updatetabelmonitoring($pengenal, $bulanpencairan);

        return response()->json(['status'=>'berhasil']);
    }
}
