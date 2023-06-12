<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DBRController extends Controller
{
    public function dbrinduk(){
        $dbrtotal = DB::table('dbrinduk')->count();
        $dbrdraft = DB::table('dbrinduk')->where('statusdbr','=',1)->count();
        $dbrunit = DB::table('dbrinduk')->where('statusdbr','=',2)->count();
        $dbrfinal = DB::table('dbrinduk')->where('statusdbr','=',3)->count();
        $pegawai = DB::table('pegawai')->get();
        $judul = "Lihat DBR";
        return view('Sirangga.Admin.dbr',[
            "judul"=>$judul,
            "dbrtotal" => $dbrtotal,
            "dbrdraft" => $dbrdraft,
            "dbrunit" => $dbrunit,
            "dbrfinal" => $dbrfinal,
            "datapegawai" => $pegawai
        ]);
    }

    public function getDataBDR(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('dbrinduk as a')
                ->select(['a.iddbr as iddbr','a.idruangan as idruangan','b.uraiangedung as gedung','c.uraianruangan as ruangan','d.nama as penanggungjawab','a.statusdbr as statusdbr',
                    'e.name as useredit','a.terakhiredit as terakhiredit','a.versike as versike','a.dokumendbr as dokumendbr',
                    'f.uraianstatus as statusdbr'])
                ->leftJoin('gedung as b','a.idgedung','=','b.id')
                ->leftJoin('ruangan as c','a.idruangan','=','c.id')
                ->leftJoin('pegawai as d','a.idpenanggungjawab','=','d.id')
                ->leftJoin('users as e','a.useredit','=','e.id')
                ->leftJoin('statusdbr as f','a.statusdbr','=','f.id')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->statusdbr == "Draft" && $row->versike == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Edit" class="edit btn btn-info btn-sm editdbr">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedbr">Delete</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Tambah Barang" class="btn btn-primary btn-sm tambahbarang">Tambah Barang</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Kirim" class="btn btn-success btn-sm kirimkeunit">Kirim</a>';
                        return $btn;
                    }elseif ($row->statusdbr == "Draft" && $row->versike >1 ){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Edit" class="edit btn btn-info btn-sm editdbr">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Tambah Barang" class="btn btn-primary btn-sm tambahbarang">Tambah Barang</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="Kirim" class="btn btn-success btn-sm kirimkeunit">Kirim</a>';
                        return $btn;
                    }elseif($row->statusdbr == "Diajukan Ke Unit"){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="ingatkanunit" class="edit btn btn-primary btn-sm ingatkanunit">Ingatkan Unit</a>';
                        return $btn;
                    }else{
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->iddbr.'" data-original-title="perubahanfinal" class="edit btn btn-primary btn-sm perubahanfinal">Perubahan Final</a>';
                        return $btn;
                    }

                })
                ->addColumn('dokumendbr',function ($row){
                    if ($row->dokumendbr != null or $row->dokumendbr != ""){
                        $linkdokumendbr = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->dokumendbr.'" >Download DBR</a>';

                    }else{
                        $linkdokumendbr = "";
                    }
                    return $linkdokumendbr;

                })
                ->rawColumns(['action','dokumendbr'])
                ->make(true);
        }
    }

    public function updatepenanggungjawabdbr(Request $request, $iddbr){
        $dataupdate = array(
            'idpenanggungjawab' => $request->get('idpenanggungjawab'),
            'useredit' => Auth::id(),
            'terakhiredit' => now()
        );
        DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);
        return response()->json(['status'=>'berhasil']);
    }

    public function editdbr($iddbr){
        $data = DB::table('dbrinduk')->where('iddbr','=',$iddbr)->get();
        return response()->json($data);
    }

    public function deletedbr(Request $request, $iddbr){
        //cek apakah ada barang didalamnya
        $adabarang = DB::table('detildbr')->where('iddbr','=',$iddbr)->count();
        if ($adabarang > 0){
            return response()->json(['status'=>'adabarang']);
        }else{
            DB::table('dbrinudk')->where('iddbr','=',$iddbr)->delete();
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function kirimdbrkeunit($iddbr){
        $adabarang = DB::table('detildbr')->where('iddbr','=',$iddbr)->count();
        if ($adabarang == 0){
            return response()->json(['status'=>'adabarang']);
        }else{
            $dataupdate = array(
                'statusdbr' => 2,
                'useredit' => Auth::id(),
                'terakhiredit' => now(),
                'tanggalpengajuanunit' => now()
            );
            DB::table('dbrinudk')->where('iddbr','=',$iddbr)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function perubahanfinal($iddbr){
        $dbr = DB::table('dbrinduk')->where('iddbr','=',$iddbr);
        $adadbr = $dbr->count();
        $datadbr = $dbr->get();
        if ($adadbr > 0){
            foreach ($datadbr as $dbr){
                $versike = $dbr->versike;
                $idpenanggungjawab = $dbr->idpenanggungjawab;
                $idgedung = $dbr->idgedung;
                $idruangan = $dbr->idruangan;
                $statusdbr = $dbr->statusdbr;
                $dibuatoleh = $dbr->dibuatoleh;
                $dibuatpada = $dbr->dibuatpada;
                $useredit = $dbr->useredit;
                $terakhiredit = $dbr->terakhiredit;
                $tanggalpengajuanunit = $dbr->tanggalpengajuanunit;
                $tanggalpersetujuandbr = $dbr->tanggalpersetujuandbr;
                $versikeawal = $dbr->versike;
                $dokumendbr = $dbr->dokumendbr;

                $datainsert = array(
                    'iddbr' => $iddbr,
                    'idpenanggungjawab' => $idpenanggungjawab,
                    'idgedung' => $idgedung,
                    'idruangan' => $idruangan,
                    'statusdbr' => $statusdbr,
                    'dibuatoleh' => $dibuatoleh,
                    'dibuatpada' => $dibuatpada,
                    'useredit' => $useredit,
                    'terakhiredit' => $terakhiredit,
                    'tanggalpengajuanunit' => $tanggalpengajuanunit,
                    'tanggalpersetujuandbr' => $tanggalpersetujuandbr,
                    'versike' =>  $versikeawal,
                    'dokumendbr' => $dokumendbr
                );
                DB::table('historydbr')->insert($datainsert);

                //copy file dokumendbr ke dok history dbr
                Storage::copy('dbrfinal/'.$dokumendbr, 'historydbrfinal/'.$dokumendbr);

                //rubah status dbr induk
                $dataupdate = array(
                    'statusdbr' => 1,
                    'useredit' => Auth::id(),
                    'terakhiredit' => now(),
                    'versike' => $versike+1
                );

                DB::table('dbrinduk')->where('iddbr','=',$iddbr)->update($dataupdate);
            }
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function tambahbarang($iddbr){

        $judul = "Lihat DBR";
        $iddbr = $iddbr;
        $datakodebarang = DB::table('listimportaset')->get();
        return view('Sirangga.Admin.dbr',[
            "judul"=>$judul,
        ]);
    }


}
