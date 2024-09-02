<?php

namespace App\Libraries;

use Twilio\Rest\Client;


class KirimWhatsapp
{


    public function formatnomerwhatsapp($nomor){
        $panjangnomor = strlen($nomor);
        $angkaawal = mb_substr($nomor,0,1);
        if ($angkaawal == "0"){
            $nomorasli = substr($nomor, 1,$panjangnomor-1);
            return "62".$nomorasli;
        }else if ($angkaawal == "6") {
            return $nomor;
        }else if ($angkaawal == "+"){
            $nomorasli = substr($nomor, 3,$panjangnomor-3);
            return "62".$nomorasli;
        }

    }

    //whartsapp dengan twilio
    public function whatsappNotification($recipient, $body)
    {
        $penerima = $this->formatnomerwhatsapp($recipient);

        $sid    = getenv("TWILIO_AUTH_SID");
        $token  = getenv("TWILIO_AUTH_TOKEN");
        $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        $twilio = new Client($sid, $token);

        return $twilio->messages->create("whatsapp:$penerima",["from" => "whatsapp:$wa_from", "body" => $body]);
    }

    //whatsapp dengan kontak
    public function kirimdbr($phonepenanggungjawab, $namapenanggungjawab, $iddbr, $tanggalakhir){
        $kepada = $this->formatnomerwhatsapp($phonepenanggungjawab);
        $token_qontak = getenv("TOKEN_QONTAK");

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'to_number' => $kepada,
                'to_name' => $namapenanggungjawab,
                'message_template_id' => '110fd5e9-1166-4a65-92f9-85ec7539ac07',
                'channel_integration_id' => '81b411ae-b566-4ec5-bb7b-361b9f66131f',
                'language' => [
                    'code' => 'id'
                ],
                'parameters' => [
                    'body' => [
                        [
                            'key' => '1',
                            'value' => 'full_name',
                            'value_text' => $namapenanggungjawab
                        ],
                        [
                            'key' => '2',
                            'value' => 'id_dbr',
                            'value_text' => $iddbr
                        ],
                        [
                            'key' => '3',
                            'value' => 'tanggal_akhir',
                            'value_text' => $tanggalakhir
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_qontak,
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return "Sukses";
        }
    }

    public function ingatkanunit($phonepenanggungjawab, $namapenanggungjawab, $iddbr, $tanggalkirimunit, $tanggalakhir){
        $kepada = $this->formatnomerwhatsapp($phonepenanggungjawab);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'to_number' => $kepada,
                'to_name' => $namapenanggungjawab,
                'message_template_id' => '0ed21018-a787-48de-b61d-09064eb87bb0',
                'channel_integration_id' => '81b411ae-b566-4ec5-bb7b-361b9f66131f',
                'language' => [
                    'code' => 'id'
                ],
                'parameters' => [
                    'body' => [
                        [
                            'key' => '1',
                            'value' => 'full_name',
                            'value_text' => $namapenanggungjawab
                        ],
                        [
                            'key' => '2',
                            'value' => 'id_dbr',
                            'value_text' => $iddbr
                        ],
                        [
                            'key' => '3',
                            'value' => 'tanggalkirim',
                            'value_text' => $tanggalkirimunit
                        ],
                        [
                            'key' => '4',
                            'value' => 'batas_akhir',
                            'value_text' => $tanggalakhir
                        ],
                        [
                            'key' => '5',
                            'value' => 'batas_akhir_asli',
                            'value_text' => $tanggalakhir
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bl862UV-wAcxCQOU-ZyvZITvo1hAfQtAWEi7PbP1PrM",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return "Sukses";
        }
    }

    public function pengajuanperubahan($namapenanggungjawab, $iddbr, $tanggalpengajuan){
        $kepada = getenv("PIC_SIRANGGA");
        $token_qontak = getenv("TOKEN_QONTAK");

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'to_number' => $kepada,
                'to_name' => "Admin Sirangga Modul Aset",
                'message_template_id' => 'ab2d19bf-582e-45a4-add2-dbd8fe8fb5b4',
                'channel_integration_id' => '81b411ae-b566-4ec5-bb7b-361b9f66131fs',
                'language' => [
                    'code' => 'id'
                ],
                'parameters' => [
                    'body' => [
                        [
                            'key' => '1',
                            'value' => 'full_name',
                            'value_text' => $namapenanggungjawab
                        ],
                        [
                            'key' => '2',
                            'value' => 'id_dbr',
                            'value_text' => $iddbr
                        ],
                        [
                            'key' => '3',
                            'value' => 'tanggalpengajuan',
                            'value_text' => $tanggalpengajuan
                        ],
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_qontak,
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return "Sukses";
        }
    }

    public function penolakandbr($namapenanggungjawab, $iddbr, $tanggalpengajuan){
        $kepada = getenv("PIC_SIRANGGA");
        $token_qontak = getenv("TOKEN_QONTAK");

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'to_number' => $kepada,
                'to_name' => "Admin Sirangga Modul Aset",
                'message_template_id' => '63c54cd9-b02a-4e7f-b8e4-59cb29865df0',
                'channel_integration_id' => '81b411ae-b566-4ec5-bb7b-361b9f66131fs',
                'language' => [
                    'code' => 'id'
                ],
                'parameters' => [
                    'body' => [
                        [
                            'key' => '1',
                            'value' => 'full_name',
                            'value_text' => $namapenanggungjawab
                        ],
                        [
                            'key' => '2',
                            'value' => 'id_dbr',
                            'value_text' => $iddbr
                        ],
                        [
                            'key' => '3',
                            'value' => 'tanggalpengajuan',
                            'value_text' => $tanggalpengajuan
                        ],
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_qontak,
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return "Sukses";
        }
    }

    public function persetujuandbr($namapenanggungjawab, $iddbr, $tanggalpengajuan){
        $kepada = getenv("PIC_SIRANGGA");
        $token_qontak = getenv("TOKEN_QONTAK");

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'to_number' => $kepada,
                'to_name' => "Admin Sirangga Modul Aset",
                'message_template_id' => '646da97d-9711-4ebf-bd3c-da88ee348240',
                'channel_integration_id' => '81b411ae-b566-4ec5-bb7b-361b9f66131fs',
                'language' => [
                    'code' => 'id'
                ],
                'parameters' => [
                    'body' => [
                        [
                            'key' => '1',
                            'value' => 'full_name',
                            'value_text' => $namapenanggungjawab
                        ],
                        [
                            'key' => '2',
                            'value' => 'id_dbr',
                            'value_text' => $iddbr
                        ],
                        [
                            'key' => '3',
                            'value' => 'tanggalpengajuan',
                            'value_text' => $tanggalpengajuan
                        ],
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_qontak,
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return "Sukses";
        }
    }
}
