<?php

namespace App\Http\Controllers\PerencanaanBMN\Bagian;

use App\Http\Controllers\Controller;
use App\Models\PerencanaanBMN\Admin\ReferensiBagianRKModel;
use App\Models\PerencanaanBMN\Admin\ReferensiBMNRKModel;
use App\Models\PerencanaanBMN\Bagian\PengajuanRKBMNBagianModel;
use App\Models\ReferensiAnggaran\KegiatanModel;
use App\Models\ReferensiAnggaran\ProgramModel;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\ReferensiUnit\BagianModel;
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
                    $idbagian = $row->biropelaksana;
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
        $data['barang'] = DB::table('detildbr as a')
            ->select(DB::raw('count(a.kd_brg) as totalbarangdbr'))
            ->leftJoin('dbrinduk as c','c.iddbr','a.iddbr')
            ->leftJoin('ruangan as d','c.idruangan','=','d.id')
            ->where('a.kd_brg','=',$kodebarang)
            ->where('d.idbagian','=',$idbagian)
            ->get(['totalbarangdbr']);
        return response()->json($data);
    }

    public function ambildatabarangtotal(Request $request){
        $kodebarang = $request->get('kodebarang');
        //$idbagian = Auth::user()->idbagian;
        $data['barang'] = DB::table('barang as a')
            ->select(DB::raw('count(a.kd_brg) as totalbarang'))
            ->where('a.kd_brg','=',$kodebarang)
            ->get(['totalbarang']);
        return response()->json($data);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idbagianpelaksana' => 'required',
            'kodebarang' => 'required',
            'uraianbarang' => 'required',
            'quantitas' => 'required',
            'hargabarang' => 'required',
            'tujuanpenggunaan' => 'required',
            'tahunpengadaan' => 'required'

        ]);

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'dokumenpendukungrkbagian','public');
        }else{
            $file = null;
        }

        $idbagianpelaksana = $request->get('idbagianpelaksana');
        $idbiropelaksana = DB::table('bagian')->where('id','=',$idbagianpelaksana)->value('idbiro');
        $quantitas = $request->get('quantitas');
        $quantitas = $this->formatulang($quantitas);
        $hargabarang = $request->get('hargabarang');
        $hargabarang = $this->formatulang($hargabarang);
        $totalanggaran = $request->get('totalanggaran');
        $totalanggaran = $this->formatulang($totalanggaran);
        $barangtersediadbr = $request->get('barangtersediadbr');
        $tahunanggaranpengusulan = $request->get('tahunpengadaan');
        $idbagianpengusul = Auth::user()->idbagian;
        if ($idbagianpengusul == null){
            $idbagianpengusul = null;
        }

        PengajuanRKBMNBagianModel::create(
            [
                'idbagianpengusul' => $idbagianpengusul,
                'idbiropengusul' => Auth::user()->idbiro,
                'idbagianpelaksana' => $idbagianpelaksana,
                'biropelaksana' => $idbiropelaksana,
                'tanggalpengajuan' => null,
                'kodebarang' => $request->get('kodebarang'),
                'barangtersediadbr' => $barangtersediadbr,
                'barangtersediatotal' => null,
                'quantitas' => $quantitas,
                'hargabarang' => $hargabarang,
                'totalanggaran' => $totalanggaran,
                'uraianbarang' => $request->get('uraianbarang'),
                'tujuanpenggunaan' => $request->get('tujuanpenggunaan'),
                'status' => "Draft",
                'dokumenpendukung' => $file,
                'tahunanggaranpengusulan' => $tahunanggaranpengusulan,
                'tahunanggaranpersetujuan' => null,
                'alasanpelaksana' => null,
                'alasanbmn' => null,
                'alasanperencanaan' => null,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

        return response()->json(['status'=>'berhasil']);
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

        $validated = $request->validate([
            'idbagianpelaksana' => 'required',
            'kodebarang' => 'required',
            'uraianbarang' => 'required',
            'quantitas' => 'required',
            'hargabarang' => 'required',
            'tujuanpenggunaan' => 'required',
            'tahunpengadaan' => 'required'

        ]);

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

        $idbagianpelaksana = $request->get('idbagianpelaksana');
        $idbiropelaksana = DB::table('bagian')->where('id','=',$idbagianpelaksana)->value('idbiro');
        $quantitas = $request->get('quantitas');
        $quantitas = $this->formatulang($quantitas);
        $hargabarang = $request->get('hargabarang');
        $hargabarang = $this->formatulang($hargabarang);
        $totalanggaran = $request->get('totalanggaran');
        $totalanggaran = $this->formatulang($totalanggaran);
        $barangtersediadbr = $request->get('barangtersediadbr');
        $tahunanggaranpengusulan = $request->get('tahunpengadaan');
        $idbagianpengusul = Auth::user()->idbagian;
        if ($idbagianpengusul == null){
            $idbagianpengusul = null;
        }


        PengajuanRKBMNBagianModel::where('id','=',$id)->update(
            [
                'idbagianpengusul' => $idbagianpengusul,
                'idbiropengusul' => Auth::user()->idbiro,
                'idbagianpelaksana' => $idbagianpelaksana,
                'biropelaksana' => $idbiropelaksana,
                'tanggalpengajuan' => now(),
                'kodebarang' => $request->get('kodebarang'),
                'barangtersediadbr' => $barangtersediadbr,
                'barangtersediatotal' => null,
                'quantitas' => $quantitas,
                'hargabarang' => $hargabarang,
                'totalanggaran' => $totalanggaran,
                'uraianbarang' => $request->get('uraianbarang'),
                'tujuanpenggunaan' => $request->get('tujuanpenggunaan'),
                'status' => "Draft",
                'dokumenpendukung' => $file,
                'tahunanggaranpengusulan' => $tahunanggaranpengusulan,
                'tahunanggaranpersetujuan' => null,
                'alasanpelaksana' => null,
                'alasanbmn' => null,
                'alasanperencanaan' => null,
                'updated_at' => now()

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
        //TODO
        //cek penggunaan referensi bagian pada tabel monitoring

        $status = DB::table('pengajuanrkbmnbagian')->where('id','=',$id)->value('status');
        if ($status == "Draft"){
            ReferensiBagianRKModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
