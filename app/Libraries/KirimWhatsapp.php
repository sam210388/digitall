<?php

namespace App\Libraries;

use Twilio\Rest\Client;


class KirimWhatsapp
{
    public function formatnomerwhatsapp($nomor){
        $panjangnomor = strlen($nomor);
        $nomorasli = substr($nomor, 1,$panjangnomor-1);
        return "+62".$nomorasli;
    }

    public function whatsappNotification($recipient, $body)
    {
        $penerima = $this->formatnomerwhatsapp($recipient);

        $sid    = getenv("TWILIO_AUTH_SID");
        $token  = getenv("TWILIO_AUTH_TOKEN");
        $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        $twilio = new Client($sid, $token);

        return $twilio->messages->create("whatsapp:$penerima",["from" => "whatsapp:$wa_from", "body" => $body]);
    }
}
