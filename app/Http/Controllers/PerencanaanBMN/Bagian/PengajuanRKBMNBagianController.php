<?php

namespace App\Http\Controllers\PerencanaanBMN\Bagian;

use App\Http\Controllers\Controller;
use App\Models\PerencanaanBMN\Admin\ReferensiBMNRKModel;
use App\Models\PerencanaanBMN\Bagian\PengajuanRKBMNBagianModel;
use App\Models\ReferensiAnggaran\KegiatanModel;
use App\Models\ReferensiAnggaran\ProgramModel;
use App\Models\Sirangga\Admin\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PengajuanRKBMNBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Pengajuan Kebutuhan BMN';
        $databmnrk = ReferensiBMNRKModel::all();
        $databagianrk = DB::table('referensibagianrk as a')
            ->select(['a.idbagian as idbagian','a.idbiro as idbiro','b.uraianbagian as uraianbagian'])
            ->leftJoin('bagian as b','a.idbagian','=','b.id')
            ->where('a.status','=','on')
            ->get(['idbagian','uraianbagian']);
        $tahunanggaran = session('tahunanggaran');
        $dataprogram = ProgramModel::where('tahunanggaran','=',$tahunanggaran)->get();
        $datakegiatan = KegiatanModel::where('tahunanggaran','=',$tahunanggaran)->get();
        $budgetYears = [
            $tahunanggaran,
            $tahunanggaran + 1,
            $tahunanggaran + 2
        ];

        if ($request->ajax()) {
            $data = PengajuanRKBMNBagianModel::all();
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    if ($row->status == "Draft"){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editpengajuan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletepengajuan">Delete</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-info btn-sm kirimkepelaksana">Ajukan</a>';
                    }else{
                        $btn = "";
                    }

                    return $btn;
                })
                ->addColumn('idbagianpelaksana',function ($row){
                    $idbagian = $row->idbagianpelaksana;
                    $uraianbiro = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbiro;
                })
                ->addColumn('biropelaksana',function ($row){
                    $idbagian = $row->idbiropelaksana;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbagian)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->addColumn('kodebarang',function ($row){
                    $kodebarang = $row->kodebarang;
                    $uraianbarang = DB::table('t_brg')->where('kd_brg','=',$kodebarang)->value('ur_sskel');
                    return $kodebarang." | ".$uraianbarang;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('PerencanaanBMN.Bagian.pengajuanrkbmnbagian',[
            "judul"=>$judul,
            "databmnrk" => $databmnrk,
            "dataprogram" => $dataprogram,
            "datakegiatan" => $datakegiatan,
            "databagianrk" => $databagianrk,
            "datatahunanggaran" => $budgetYears
        ]);
    }

    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
    }

    public function kirimkepelaksanapengadaan($id){
        //cek data
        $adadata = DB::table('pengajuanrkbmnbagian')->where('id','=',$id)->count();
        if ($adadata >0){
            //cek status
            $status = DB::table('pengajuanrkbmnbagian')->where('id','=',$id)->value('status');
            if ($status == "Draft"){
                //ubah status
                DB::table('pengajuanrkbmnbagian')->where('id','=',$id)->update([
                    'tanggalpengajuan' => now(),
                    'updated_at' => now(),
                    'status' => "Diajukan Ke Unit Pelaksana"
                ]);
                return response()->json(['status'=>'berhasil']);
            }else{
                return response()->json(['status'=>'gagal']);
            }
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function ambildatabarangdalamdbr(Request $request){
        $kodebarang = $request->get('kodebarang');
        $idbagian = Auth::user()->idbagian;

        // Mengambil data barang dalam detildbr
        $dataBarangDalamDbr = DB::table('detildbr as a')
            ->select(DB::raw('count(a.kd_brg) as totalbarangdbr'))
            ->leftJoin('dbrinduk as c','c.iddbr','a.iddbr')
            ->leftJoin('ruangan as d','c.idruangan','=','d.id')
            ->where('a.kd_brg','=',$kodebarang)
            ->where('d.idbagian','=',$idbagian)
            ->get()->toArray();

        // Mengambil data total barang
        $dataBarangTotal = DB::table('barang as a')
            ->select(DB::raw('count(a.kd_brg) as totalbarang'))
            ->where('a.kd_brg','=',$kodebarang)
            ->get()->toArray();

        // Menggabungkan dua array
        $data = array_merge($dataBarangDalamDbr, $dataBarangTotal);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validatedUmum = $request->validate([
            'idbagianpelaksana' => 'required',
            'uraianbarang' => 'required',
            'quantitas' => 'required',
            'hargabarang' => 'required',
            'totalanggaran' => 'required',
            'tahunpengadaan' => 'required',
            'skema' => 'required',
            'program' => 'required',
            'kegiatan' => 'required',
            'output' => 'required',
            'kodebarang' => 'required',
            'keterangan' => '',
            'barangtersediadbr' => 'required',
            'barangtersediatotal' => 'required',
        ]);

        $jenistabel = $request->get('jenistabel');
        if ($jenistabel == "R1" || $jenistabel == "R2"){
            $validatedkhusus = $request->validate([
                'tujuanrencana' => 'required',
                'atrnonatr' => 'required',
                'jeniskantor' => 'required',
                'jenispengadaan' => 'required',
                'lokasi' => 'required',
                'luas' => 'required'
            ]);

            $tujuanrencana = $validatedkhusus['tujuanrencana'];
            $atrnonatr = $validatedkhusus['tujuanrencana'];
            $jeniskantor = $validatedkhusus['jeniskantor'];
            $jenispengadaan = $validatedkhusus['jenispengadaan'];
            $lokasi = $validatedkhusus['lokasi'];
            $luas = $validatedkhusus['luas'];
            if ($jenistabel == "R1"){
                $akunbelanja = "531111 - Belanja Modal Tanah";
                $akunneraca = "131111 - Tanah";
            }else{
                $akunbelanja = "533111 Belanja Modal Gedung dan Bangunan";
                $akunneraca = "133111 - Gedung dan Bangunan";
            }

            $datainsertkhusus = array(
                'tujuan' => $tujuanrencana,
                'atrnonatr' => $atrnonatr,
                'jeniskantor' => $jeniskantor,
                'jenispengadaan' => $jenispengadaan,
                'lokasi' => $lokasi,
                'luas' => $luas,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );

        }else if ($jenistabel == "R3"){
            $validatedkhusus = $request->validate([
                'pejabatpemakai' => 'required',
                'spesifikasi' => 'required'
            ]);

            $pejabatpemakai = $validatedkhusus['pejabatpemakai'];
            $spesifikasi = $validatedkhusus['spesifikasi'];
            $akunbelanja = "532111 - Belanja Modal Peralatan dan Mesin";
            $akunneraca = "132111 - Peralatan dan Mesin";

            $datainsertkhusus = array(
                'pejabatpemakai' => $pejabatpemakai,
                'spesifikasi' => $spesifikasi,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );

        }else if ($jenistabel == "R4"){
            $validatedkhusus = $request->validate([
                'jenissatker' => 'required',
                'jeniskendaraan' => 'required'
            ]);

            $jenissatker = $validatedkhusus['jenissatker'];
            $jeniskendaraan = $validatedkhusus['jeniskendaraan'];
            $akunbelanja = "532111 - Belanja Modal Peralatan dan Mesin";
            $akunneraca = "132111 - Peralatan dan Mesin";

            $datainsertkhusus = array(
                'jenissatker' => $jenissatker,
                'jeniskendaraan' => $jeniskendaraan,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );
        }

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'dokumenpendukungrkbagian','public');
        }else{
            $file = null;
        }

        $idbagianpelaksana = $validatedUmum['idbagianpelaksana'];
        $idbiropelaksana = DB::table('bagian')->where('id','=',$validatedUmum['idbagianpelaksana'])->value('idbiro');
        $uraianbarang = $validatedUmum['uraianbarang'];
        $quantitas = $this->formatulang($validatedUmum['quantitas']);
        $hargabarang = $this->formatulang($validatedUmum['hargabarang']);
        $totalanggaran = $this->formatulang($validatedUmum['totalanggaran']);
        $tahunanggaranpengusulan = $validatedUmum['tahunpengadaan'];
        $skema = $validatedUmum['skema'];
        $program = $validatedUmum['program'];
        $kegiatan = $validatedUmum['kegiatan'];
        $output = $validatedUmum['output'];
        $kodebarang = $validatedUmum['kodebarang'];
        $keterangan = $validatedUmum['keterangan'];
        $barangtersediadbr = $validatedUmum['barangtersediadbr'];
        $barangtersediatotal = $validatedUmum['barangtersediatotal'];
        $idbagianpengusul = Auth::user()->idbagian;
        $idbiropengusul = DB::table('bagian')->where('id','=',$idbagianpengusul)->value('idbiro');

        $datainsertumum = array(
            'jenistabel' => $jenistabel,
            'idbagianpengusul' => $idbagianpengusul,
            'idbiropengusul' => $idbiropengusul,
            'idbagianpelaksana' => $idbagianpelaksana,
            'idbiropelaksana'=> $idbiropelaksana,
            'skema' => $skema,
            'program' => $program,
            'kegiatan' => $kegiatan,
            'output' => $output,
            'kodebarang' => $kodebarang,
            'barangtersediadbr' => $barangtersediadbr,
            'barangtersediatotal' => $barangtersediatotal,
            'quantitas' => $quantitas,
            'hargabarang' => $hargabarang,
            'totalanggaran' => $totalanggaran,
            'uraianbarang' => $uraianbarang,
            'keterangan' => $keterangan,
            'status' => "Draft",
            'dokumenpendukung' => $file,
            'tahunanggaranpengusulan' => $tahunanggaranpengusulan
        );

        $datainsert = array_merge($datainsertkhusus, $datainsertumum);

        $pengajuan = PengajuanRKBMNBagianModel::create($datainsert);

        if ($pengajuan) {
            return response()->json(['status' => 'berhasil']);
        } else {
            return response()->json(['status' => 'gagal'], 500);
        }
    }


    public function edit($id)
    {
        $menu = PengajuanRKBMNBagianModel::find($id);
        return response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validatedUmum = $request->validate([
            'idbagianpelaksana' => 'required',
            'uraianbarang' => 'required',
            'quantitas' => 'required',
            'hargabarang' => 'required',
            'totalanggaran' => 'required',
            'tahunpengadaan' => 'required',
            'skema' => 'required',
            'program' => 'required',
            'kegiatan' => 'required',
            'output' => 'required',
            'kodebarang' => 'required',
            'keterangan' => '',
            'barangtersediadbr' => 'required',
            'barangtersediatotal' => 'required',
        ]);

        $jenistabel = $request->get('jenistabel');
        if ($jenistabel == "R1" || $jenistabel == "R2"){
            $validatedkhusus = $request->validate([
                'tujuanrencana' => 'required',
                'atrnonatr' => 'required',
                'jeniskantor' => 'required',
                'jenispengadaan' => 'required',
                'lokasi' => 'required',
                'luas' => 'required'
            ]);

            $tujuanrencana = $validatedkhusus['tujuanrencana'];
            $atrnonatr = $validatedkhusus['tujuanrencana'];
            $jeniskantor = $validatedkhusus['jeniskantor'];
            $jenispengadaan = $validatedkhusus['jenispengadaan'];
            $lokasi = $validatedkhusus['lokasi'];
            $luas = $validatedkhusus['luas'];
            if ($jenistabel == "R1"){
                $akunbelanja = "531111 - Belanja Modal Tanah";
                $akunneraca = "131111 - Tanah";
            }else{
                $akunbelanja = "533111 Belanja Modal Gedung dan Bangunan";
                $akunneraca = "133111 - Gedung dan Bangunan";
            }

            $datainsertkhusus = array(
                'tujuan' => $tujuanrencana,
                'atrnonatr' => $atrnonatr,
                'jeniskantor' => $jeniskantor,
                'jenispengadaan' => $jenispengadaan,
                'lokasi' => $lokasi,
                'luas' => $luas,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );

        }else if ($jenistabel == "R3"){
            $validatedkhusus = $request->validate([
                'pejabatpemakai' => 'required',
                'spesifikasi' => 'required'
            ]);

            $pejabatpemakai = $validatedkhusus['pejabatpemakai'];
            $spesifikasi = $validatedkhusus['spesifikasi'];
            $akunbelanja = "532111 - Belanja Modal Peralatan dan Mesin";
            $akunneraca = "132111 - Peralatan dan Mesin";

            $datainsertkhusus = array(
                'pejabatpemakai' => $pejabatpemakai,
                'spesifikasi' => $spesifikasi,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );

        }else if ($jenistabel == "R4"){
            $validatedkhusus = $request->validate([
                'jenissatker' => 'required',
                'jeniskendaraan' => 'required'
            ]);

            $jenissatker = $validatedkhusus['jenissatker'];
            $jeniskendaraan = $validatedkhusus['jeniskendaraan'];
            $akunbelanja = "532111 - Belanja Modal Peralatan dan Mesin";
            $akunneraca = "132111 - Peralatan dan Mesin";

            $datainsertkhusus = array(
                'jenissatker' => $jenissatker,
                'jeniskendaraan' => $jeniskendaraan,
                'akunbelanja' => $akunbelanja,
                'akunneraca' => $akunneraca
            );
        }

        $dokumenpendukungawal = $request->get('dokumenpendukungawal');
        if ($request->file('file')){
            if (file_exists(storage_path('app/public/').$dokumenpendukungawal)){
                Storage::delete('public/'.$dokumenpendukungawal);
            }
            $file = $request->file('file')->store(
                'dokumenpendukungrkbagian','public');
        }else{
            $file = $dokumenpendukungawal;
        }

        $idbagianpelaksana = $validatedUmum['idbagianpelaksana'];
        $idbiropelaksana = DB::table('bagian')->where('id','=',$validatedUmum['idbagianpelaksana'])->value('idbiro');
        $uraianbarang = $validatedUmum['uraianbarang'];
        $quantitas = $this->formatulang($validatedUmum['quantitas']);
        $hargabarang = $this->formatulang($validatedUmum['hargabarang']);
        $totalanggaran = $this->formatulang($validatedUmum['totalanggaran']);
        $tahunanggaranpengusulan = $validatedUmum['tahunpengadaan'];
        $skema = $validatedUmum['skema'];
        $program = $validatedUmum['program'];
        $kegiatan = $validatedUmum['kegiatan'];
        $output = $validatedUmum['output'];
        $kodebarang = $validatedUmum['kodebarang'];
        $keterangan = $validatedUmum['keterangan'];
        $barangtersediadbr = $validatedUmum['barangtersediadbr'];
        $barangtersediatotal = $validatedUmum['barangtersediatotal'];
        $idbagianpengusul = Auth::user()->idbagian;
        $idbiropengusul = DB::table('bagian')->where('id','=',$idbagianpengusul)->value('idbiro');

        $datainsertumum = array(
            'jenistabel' => $jenistabel,
            'idbagianpengusul' => $idbagianpengusul,
            'idbiropengusul' => $idbiropengusul,
            'idbagianpelaksana' => $idbagianpelaksana,
            'idbiropelaksana'=> $idbiropelaksana,
            'skema' => $skema,
            'program' => $program,
            'kegiatan' => $kegiatan,
            'output' => $output,
            'kodebarang' => $kodebarang,
            'barangtersediadbr' => $barangtersediadbr,
            'barangtersediatotal' => $barangtersediatotal,
            'quantitas' => $quantitas,
            'hargabarang' => $hargabarang,
            'totalanggaran' => $totalanggaran,
            'uraianbarang' => $uraianbarang,
            'keterangan' => $keterangan,
            'status' => "Draft",
            'dokumenpendukung' => $file,
            'tahunanggaranpengusulan' => $tahunanggaranpengusulan
        );

        $datainsert = array_merge($datainsertkhusus, $datainsertumum);

        $pengajuan = PengajuanRKBMNBagianModel::where('id','=',$id)->update($datainsert);

        if ($pengajuan) {
            return response()->json(['status' => 'berhasil']);
        } else {
            return response()->json(['status' => 'gagal'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = DB::table('pengajuanrkbmnbagian')->where('id','=',$id)->value('status');
        if ($status == "Draft"){
            PengajuanRKBMNBagianModel::where('id','=',$id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
