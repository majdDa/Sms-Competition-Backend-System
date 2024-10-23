<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IMTNRepository;
use Illuminate\Support\Facades\Log;

use  SoapFault;
use SoapClient;

class MTNRepository implements IMTNRepository
{

    public function call_activation_api($request)
    {
        //    dd($request->sc);
        $result = '';
        $fee = 0;
        $action = 'A';
        $gsm = $request->gsm;
        $sc = $request->sc;
        $service_name = '90MinCompetition';
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        try {
            if ($sc == '1890') {
                $fee = 125;
            } else {
                $fee = 0;
            }
            $url = "https://services.mtnsyr.com:7443/General/mtnservices/chargingservice.asmx/takeAction?username=Rand12&password=Rand12345&service_name={$service_name}&gsm={$gsm}&category=D&fee={$fee}&action={$action}&msgno=1111";
            //dd($url);
            $g = file_get_contents($url);
            $xml = simplexml_load_string($g);
            $json = json_encode($xml);
            $phpArray = json_decode($json, true);
            $result = $phpArray[0];
        } catch (SoapFault $e) {
            $result = $e->getMessage();
        }
        //add log
        Log::channel('Api_Act_mtn')->info(date("Y-m-d h:i:sa"), [$url, $result]);

        return $result;
    }



    public function call_deactivation_api($gsm)
    {
        $result = '';
        $service_name = '90MinCompetition';
        $action = 'D';
        $fee = 0;
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        try {
            $url = "https://services.mtnsyr.com:7443/General/mtnservices/chargingservice.asmx/takeAction?username=Rand12&password=Rand12345&service_name={$service_name}&gsm={$gsm}&category=D&fee={$fee}&action={$action}&msgno=1111";
            $g = file_get_contents($url);
            $xml = simplexml_load_string($g);
            $json = json_encode($xml);

            $phpArray = json_decode($json, true);
            $result = $phpArray[0];
        } catch (SoapFault $e) {
            $result = $e->getMessage();
        }
        Log::channel('Api_DeAct_mtn')->info(date("Y-m-d h:i:sa"), [$url, $result]);

        return $result;
    }





    public function call_renewal_api($gsm)
    {
        $result = '';
        $service_name = '90MinCompetition';
        $action = 'R';
        $fee = 125;
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        try {
            $url = "https://services.mtnsyr.com:7443/General/mtnservices/chargingservice.asmx/takeAction?username=Rand12&password=Rand12345&service_name={$service_name}&gsm={$gsm}&category=D&fee={$fee}&action={$action}&msgno=1111";
            // dd($url);
            $g = file_get_contents($url);
            $xml = simplexml_load_string($g);
            $json = json_encode($xml);

            $phpArray = json_decode($json, true);
            $result = $phpArray[0];
        } catch (SoapFault $e) {
            $result = $e->getMessage();
        }
        Log::channel('renewal_mtn')->info(date("Y-m-d h:i:sa"), [$url, $result]);
        return $result;
    }





    public function send_sms($gsm, $sms)
    {

        $curl = curl_init();
        $sc = '1890';
        $sms = mb_convert_encoding((bin2hex(mb_convert_encoding($sms, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8');
        $sms = iconv('UCS-2', 'UTF-8', $sms);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://services.mtnsyr.com:7443/General/MTNSERVICES/ConcatenatedSender.aspx?User=Rand12&Pass=Rand12345&From=' . $sc . '&Gsm=' . $gsm . '&Msg=' . $sms . '&Lang=0',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    public function formatSMS($sms)
    {

        return str_replace("'", "", $this->bin2utf8($sms));
    }

    public function hexaSMS($sms)
    {

        return iconv('UCS-2', 'UTF-8', mb_convert_encoding((bin2hex(mb_convert_encoding($sms, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8'));
    }


    public function local_hex2bin($h)
    {
        if (!is_string($h)) {
            return null;
        }
        $r = '';
        $length = strlen($h);

        for ($a = 0; $a < $length; $a += 2) {
            $r .= chr(hexdec($h[$a] . $h[($a + 1)]));
        }
        return $r;
    }



    public function bin2utf8($str)
    {
        $ucs2string = $this->local_hex2bin($str);
        $utf8string = mb_convert_encoding($ucs2string, 'UTF-8', 'UCS-2');
        return $utf8string;
    }
}
