<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\ISyriatelRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class SyriatelRepository implements ISyriatelRepository
{
    public function call_activation_api($gsm)
    {
        $gsm = substr($gsm, 4);
        $url = 'https://bulk.syriatel.com.sy/mt/spservices?gsm=' . $gsm . '&action=Act&service=90MIN_C&res=1';
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $operatorResponse = file_get_contents($url, false, stream_context_create($arrContextOptions));
        Log::channel('Api_Act_sy')->info(date("Y-m-d h:i:sa"), [$url, $operatorResponse]);

        return $operatorResponse;
    }

    public function call_deActivation_api($gsm)
    {
        $gsm = substr($gsm, 4);
        $url = 'https://bulk.syriatel.com.sy/mt/spservices?gsm=' . $gsm . '&action=Deact&service=90MIN_C&res=1';
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $operatorResponse = file_get_contents($url, false, stream_context_create($arrContextOptions));
        Log::channel('Api_DeAct_sy')->info(date("Y-m-d h:i:sa"), [$url, $operatorResponse]);

        return $operatorResponse;
    }

    public function send_sms($gsm, $mt)
    {
        $sc = '1890';
        $mt = mb_convert_encoding((bin2hex(mb_convert_encoding($mt, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8');
        $mt = iconv('UCS-2', 'UTF-8', $mt);
        $url = 'https://bulk.syriatel.com.sy/mt/mt?orig=' . $sc . '&dest=' . $gsm . '&msg=' . $mt . '&type=1&res=C';
        $operatorResponse = file_get_contents($url);
        return $operatorResponse;
    }

    public function send_bulk($teserText)
    {

        $agency = "RAND";
        $cat = '90MIN_C';
        $teserText = str_replace('"', ' ', $teserText);
        $teserText = $this->sms__unicode($teserText);
        $lang = 'Ar';

        $url = "https://bulk.syriatel.com.sy/mt/news?agency={$agency}&cat={$cat}&msg={$teserText}&lang={$lang}&res=N";
        echo $url . "<br>";
        try {
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            $g_syriatel_response = file_get_contents($url, false, stream_context_create($arrContextOptions));
            echo $g_syriatel_response . "<br>";
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return $g_syriatel_response;
    }


    private function sms__unicode($message)
    {
        if (function_exists('iconv')) {
            $latin = @iconv('UTF-8', 'ISO-8859-1', $message);
            if (strcmp($latin, $message)) {
                $arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
                return $arr['hex'];
            }
        }
        return FALSE;
    }
}