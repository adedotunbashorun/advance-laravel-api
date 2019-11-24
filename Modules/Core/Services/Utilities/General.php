<?php

namespace Modules\Core\Services\Utilities;

use stdClass;

class General{


    private function _curlRequest($url,$accessToken = "")
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken, "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }

    function curlRestRequest($method, $endpoint, $params,$accessToken)
    {

        $ch = $this->_curlRequest($endpoint, $accessToken);

        $method = strtoupper($method);

        switch ($method)
        {
            case "GET":
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                break;
            default:
                throw new Exception("Error: Invalid HTTP method '$method' $endpoint");

                return null;
        }

        $oRet = new StdClass;
        $oRet->response = json_decode(curl_exec($ch));
        $oRet->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        \Log::info((array)$oRet);
        // dd($oRet);


        if (isset($oRet->status) && $oRet->status >= 200 &&  $oRet->status < 400)
            return $oRet->response;

        if($oRet->status >= 400)
            throw new \Exception('Bad Request ' . $oRet->response->message);

    }
}
