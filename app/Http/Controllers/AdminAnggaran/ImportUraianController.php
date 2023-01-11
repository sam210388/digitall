<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportUraianController extends Controller
{
    function importprogram(){
        //tarik data dari monsakti
        $bearerkey = new BearerKeyController();
        $bearerkey = $bearerkey->dapatkanbearerkey();
        $key = $bearerkey;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://monsakti.kemenkeu.go.id/sitp-monsakti-omspan/webservice/API/KL002/refUraian/program',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $diolah = substr(json_encode($response),10,14);
        if ($diolah !== "" && $diolah !== "<b>Fatal error" ) {
            $hasilasli = json_decode($response);
            foreach ($hasilasli as $ITEM) {
                $THANG = $ITEM->THANG;
                $KODE = $ITEM->KODE;
                $DESKRIPSI = $ITEM->DESKRIPSI;

                $where = array(
                    'thang' => $THANG,
                    'kode' => $KODE,
                    'deskripsi' => $DESKRIPSI
                );

                $jumlah = Program::where($where)->get()->count();
                if ($jumlah == 0){
                    $data = array(
                        'thang' => $THANG,
                        'kode' => $KODE,
                        'deskripsi' => $DESKRIPSI
                    );
                    Program::insert($data);
                }
            }
            return redirect()->to('anggaran/program')->with('status','Import Program Berhasil');
        }
    }
}
