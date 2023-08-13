<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;


class PegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    public function ambildatapegawai(Request $request)
    {
        $email = $request->get('email');
        $menu = PegawaiModel::where('email','=',$email)->get();
        return response($menu);
    }


    public function pegawai(){
        $judul = "List Pegawai";
        return view('Administrasi.pegawai',[
            "judul"=>$judul
        ]);
    }
    public function getlistpegawai(Request $request)
    {
        if ($request->ajax()) {
            $data = PegawaiModel::orderBy('id','DESC');
            return Datatables::of($data)
                ->addColumn('satker', function($row){
                    $id_satker = $row->id_satker;
                    $uraiansatker = DB::table('apisiapunit')->where('id','=',$id_satker)->value('nama_satker');
                    return $uraiansatker;
                })
                ->addColumn('subsatker', function($row){
                    $id_subsatker = $row->id_subsatker;
                    if ($id_subsatker == null){
                        $uraiansubsatker = "";
                    }else{
                        $uraiansubsatker = DB::table('apisiapunit')->where('id','=',$id_subsatker)->value('nama_satker');
                    }
                    return $uraiansubsatker;
                })
                ->make(true);
        }
    }

    //import user dari siap
    function importsiap(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://siap.dpr.go.id/api-rest/angestellter',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'geheimagent=s4mb3n3k3YP4ttY',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: PHPSESSID=ru0ive16k7a7lpo57kljts8dh5'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $hasil = json_decode($response);
        $result = $hasil->result;
        $cekisi = json_encode($hasil);
        //DB::table('pegawai')->truncate();

        foreach ($result as $item){
                $id = $item->id;
                $nama = $item->nama;
                $nip = $item->nip;
                $nama_satker = $item->nama_satker;
                $id_satker = $item->id_satker;
                $email = $item->email;
                $phone = $item->handphone;
                $id_subsatker = $item->id_subsatker;
                $eslelon = $item->eselon;

                $data = array(
                    'id' => $id,
                    'nama' => $nama,
                    'nip' => $nip,
                    'nama_satker' => $nama_satker,
                    'id_satker' => $id_satker,
                    'email' => $email,
                    'phone' => $phone,
                    'id_subsatker' => $id_subsatker,
                    'eselon' => $eslelon
                );

                PegawaiModel::updateOrCreate(['id' => $id],$data);
        }


        //return response()->json($cekisi);
        return redirect()->to('pegawai')->with(['status' => "Import Pegawai dari SIAP berhasil"]);
    }
}
