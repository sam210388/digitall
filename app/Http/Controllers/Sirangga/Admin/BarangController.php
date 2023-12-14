<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Exports\ExportDataBarang;
use App\Exports\ExportDetilDBR;
use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dapatkandataaset(Request $request){
        $data['barang'] = DB::table('barang')
            ->where('kd_brg','=',$request->kodebarang)
            ->get(['no_aset','no_aset']);
        return response()->json($data);
    }

    public function barang(){
        $judul = "Data Barang";
        $barang = DB::table('barang')->count();
        $statushenti = DB::table('barang')->where('statushenti','=',2)->count();
        $statususul = DB::table('barang')->where('statususul','=',2)->count();
        $statushapus = DB::table('barang')->where('statushapus','=',2)->count();
        return view('Sirangga.Admin.barang',[
            "judul"=>$judul,
            "totalbarang" => $barang,
            "statushenti" => $statushenti,
            "statususul" => $statususul,
            "statushapus" => $statushapus
        ]);
    }

    public function getdatabarang(){
        $data = BarangModel::with('kodebarangrelation')
            ->select('barang.*');

        return Datatables::eloquent($data)
            ->addColumn('ur_sskel', function (BarangModel $barang) {
                return $barang->kodebarangrelation->ur_sskel;
            })
            ->editColumn('statusdbr', function ($row) {
                if ($row->statusdbr == 2){
                    $kd_lokasi = $row->kd_lokasi;
                    $kd_brg = $row->kd_brg;
                    $no_aset = $row->no_aset;
                    $iddbr = DB::table('detildbr')
                        ->where('kd_lokasi','=',$kd_lokasi)
                        ->where('kd_brg','=',$kd_brg)
                        ->where('no_aset','=',$no_aset)
                        ->value('iddbr');
                    return "IDDBR ".$iddbr;
                }else{
                    return "Belum DBR";
                }
            })
            ->toJson();

    }

    function exportdatabarang($statusbarang){
        return Excel::download(new ExportDataBarang($statusbarang),'DataBarang.xlsx');
    }

    function aksiupdatestatushenti(){
        $ambildatastatushenti = DB::table('penghentianpenggunaan')->get();
        if ($ambildatastatushenti){
            foreach ($ambildatastatushenti as $item){
                $kduakpb = $item->kduakpb;
                $kdbrg = $item->kdbrg;
                $nup = $item->nup;
                $tgl_buku = $item->tgl_buku;

                //cek ke kode barang
                $where = array(
                    'kd_lokasi' => $kduakpb,
                    'kd_brg' => $kdbrg,
                    'no_aset' => $nup
                );

                $dataupdate = DB::table('barang')->where($where)->update([
                    'statushenti' => 2,
                    'tanggalhenti' => $tgl_buku
                ]);

                //ambil ID Detil jika ada
                $DBR = DB::table('detildbr')->where($where);
                $tercatatdbr = $DBR->count();
                $tercatattidaknormal = DB::table('detildbrtidaknormal')->where($where)->count();
                if ($tercatatdbr >0 and $tercatattidaknormal == 0){
                    //jika ada DBR, ambil data detilnya
                    $datadetil = $DBR->get();
                    foreach ($datadetil as $item) {
                        $iddetil = $item->iddetil;
                        $iddbr = $item->iddbr;
                        $idbarang = $item->idbarang;
                        $kd_lokasi = $item->kd_lokasi;
                        $kd_brg = $item->kd_brg;
                        $no_aset = $item->no_aset;
                        $uraianbarang = $item->uraianbarang;
                        $tahunperolehan = $item->tahunperolehan;
                        $merek = $item->merek;
                        $statusbarcode = $item->statusbarcode;
                        $iduser = $item->iduser;
                        $waktumasukdbr = $item->waktumasukdbr;
                        $waktukeluardbr = $item->waktukeluardbr;
                        $statusbarang = $item->statusbarang;
                        $terakhirperiksa = $item->terakhirperiksa;
                        $diperiksaoleh = $item->diperiksaoleh;

                        $datainsert = array(
                            'iddetil' => $iddetil,
                            'iddbr' => $iddbr,
                            'idbarang' => $idbarang,
                            'kd_lokasi' => $kd_lokasi,
                            'kd_brg' => $kd_brg,
                            'no_aset' => $no_aset,
                            'uraianbarang' => $uraianbarang,
                            'tahunperolehan' => $tahunperolehan,
                            'merek' => $merek,
                            'statusbarcode' => $statusbarcode,
                            'iduser' => $iduser,
                            'waktumasukdbr' => $waktumasukdbr,
                            'waktukeluardbr' => $waktukeluardbr,
                            'statusbarang' => $statusbarang,
                            'terakhirperiksa' => $terakhirperiksa,
                            'diperiksaoleh' => $diperiksaoleh
                        );
                        DB::table('detildbrtidaknormal')->insert($datainsert);
                    }

                }
            }
        }
    }

    function aksiupdatestatususul(){
        $ambildatastatushenti = DB::table('pengusulanpenghapusan')->get();
        if ($ambildatastatushenti){
            foreach ($ambildatastatushenti as $item){
                $kduakpb = $item->kduakpb;
                $kdbrg = $item->kdbrg;
                $nup = $item->nup;
                $tgl_buku = $item->tgl_buku;

                //cek ke kode barang
                $where = array(
                    'kd_lokasi' => $kduakpb,
                    'kd_brg' => $kdbrg,
                    'no_aset' => $nup
                );

                $dataupdate = DB::table('barang')->where($where)->update([
                    'statususul' => 2,
                    'tanggalusul' => $tgl_buku
                ]);

                //ambil ID Detil jika ada
                $DBR = DB::table('detildbr')->where($where);
                $tercatatdbr = $DBR->count();
                $tercatattidaknormal = DB::table('detildbrtidaknormal')->where($where)->count();
                if ($tercatatdbr >0 and $tercatattidaknormal == 0){
                    //jika ada DBR, ambil data detilnya
                    $datadetil = $DBR->get();
                    foreach ($datadetil as $item) {
                        $iddetil = $item->iddetil;
                        $iddbr = $item->iddbr;
                        $idbarang = $item->idbarang;
                        $kd_lokasi = $item->kd_lokasi;
                        $kd_brg = $item->kd_brg;
                        $no_aset = $item->no_aset;
                        $uraianbarang = $item->uraianbarang;
                        $tahunperolehan = $item->tahunperolehan;
                        $merek = $item->merek;
                        $statusbarcode = $item->statusbarcode;
                        $iduser = $item->iduser;
                        $waktumasukdbr = $item->waktumasukdbr;
                        $waktukeluardbr = $item->waktukeluardbr;
                        $statusbarang = $item->statusbarang;
                        $terakhirperiksa = $item->terakhirperiksa;
                        $diperiksaoleh = $item->diperiksaoleh;

                        $datainsert = array(
                            'iddetil' => $iddetil,
                            'iddbr' => $iddbr,
                            'idbarang' => $idbarang,
                            'kd_lokasi' => $kd_lokasi,
                            'kd_brg' => $kd_brg,
                            'no_aset' => $no_aset,
                            'uraianbarang' => $uraianbarang,
                            'tahunperolehan' => $tahunperolehan,
                            'merek' => $merek,
                            'statusbarcode' => $statusbarcode,
                            'iduser' => $iduser,
                            'waktumasukdbr' => $waktumasukdbr,
                            'waktukeluardbr' => $waktukeluardbr,
                            'statusbarang' => $statusbarang,
                            'terakhirperiksa' => $terakhirperiksa,
                            'diperiksaoleh' => $diperiksaoleh
                        );
                        DB::table('detildbrtidaknormal')->insert($datainsert);
                    }

                }
            }
        }
    }

    function aksiuodatestatushapus(){
        $ambildatastatushenti = DB::table('penghapusanbarang')->get();
        if ($ambildatastatushenti){
            foreach ($ambildatastatushenti as $item){
                $kduakpb = $item->kduakpb;
                $kdbrg = $item->kdbrg;
                $nup = $item->nup;
                $tgl_buku = $item->tgl_buku;

                //cek ke kode barang
                $where = array(
                    'kd_lokasi' => $kduakpb,
                    'kd_brg' => $kdbrg,
                    'no_aset' => $nup
                );

                $dataupdate = DB::table('barang')->where($where)->update([
                    'statushapus' => 2,
                    'tanggalhapus' => $tgl_buku
                ]);

                //ambil ID Detil jika ada
                $DBR = DB::table('detildbr')->where($where);
                $tercatatdbr = $DBR->count();
                $tercatattidaknormal = DB::table('detildbrtidaknormal')->where($where)->count();
                if ($tercatatdbr >0 and $tercatattidaknormal == 0){
                    //jika ada DBR, ambil data detilnya
                    $datadetil = $DBR->get();
                    foreach ($datadetil as $item) {
                        $iddetil = $item->iddetil;
                        $iddbr = $item->iddbr;
                        $idbarang = $item->idbarang;
                        $kd_lokasi = $item->kd_lokasi;
                        $kd_brg = $item->kd_brg;
                        $no_aset = $item->no_aset;
                        $uraianbarang = $item->uraianbarang;
                        $tahunperolehan = $item->tahunperolehan;
                        $merek = $item->merek;
                        $statusbarcode = $item->statusbarcode;
                        $iduser = $item->iduser;
                        $waktumasukdbr = $item->waktumasukdbr;
                        $waktukeluardbr = $item->waktukeluardbr;
                        $statusbarang = $item->statusbarang;
                        $terakhirperiksa = $item->terakhirperiksa;
                        $diperiksaoleh = $item->diperiksaoleh;

                        $datainsert = array(
                            'iddetil' => $iddetil,
                            'iddbr' => $iddbr,
                            'idbarang' => $idbarang,
                            'kd_lokasi' => $kd_lokasi,
                            'kd_brg' => $kd_brg,
                            'no_aset' => $no_aset,
                            'uraianbarang' => $uraianbarang,
                            'tahunperolehan' => $tahunperolehan,
                            'merek' => $merek,
                            'statusbarcode' => $statusbarcode,
                            'iduser' => $iduser,
                            'waktumasukdbr' => $waktumasukdbr,
                            'waktukeluardbr' => $waktukeluardbr,
                            'statusbarang' => $statusbarang,
                            'terakhirperiksa' => $terakhirperiksa,
                            'diperiksaoleh' => $diperiksaoleh
                        );
                        DB::table('detildbrtidaknormal')->insert($datainsert);
                    }

                }
            }
        }
    }



}
