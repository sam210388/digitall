<?php

namespace App\Libraries;

class TarikDataMonsakti
{
    function tarikdata($token, $kodemodul, $tipedata, Array $variable)
    {
        $jumlahvariable = count($variable);
        $variabelopsional = '';
        for ($i = 0; $i<=$jumlahvariable-1; $i++ ){
            $variable = $variable[$i];
            $variabelopsional = $variabelopsional . '/' . $variable;
        }

        $url = 'https://monsakti.kemenkeu.go.id/sitp-monsakti-omspan/webservice/API/' . $kodemodul . '/' . $tipedata . '/KL002' . $variabelopsional;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }


    function cekresponse($response){
        //cek response
        $diolah = substr(json_encode($response),0,50);
        if (str_contains($diolah, "Expired")){
            return "Expired";

        }else if (str_contains($diolah,"Fatal")){
            return "Gagal";
        }else{
            return $response;
        }
    }

    function prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable){
        //ambil berarer key
        $token = new BearerKey();
        $token = $token->dapatkanbearerkey($tahunanggaran, $kodemodul);

        //coba tarik data
        $data = $this->tarikdata($token, $kodemodul, $tipedata, $variable);

        //cek responsenya
        $cekdata = $this->cekresponse($data);
        return $cekdata;

    }

}
