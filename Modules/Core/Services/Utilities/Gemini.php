<?php

namespace Modules\Core\Services\Utilities;

class Gemini extends General
{
    protected $baseUrl, $cliendId, $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('pennylender.gemini.base_url');
        $this->cliendId = config('pennylender.gemini.client_id');
        $this->apiKey = config('pennylender.gemini.api_key');
    }

    public function getBanks(){
        return $this->curlRestRequest("GET", $this->baseUrl."/banks", null, $this->cliendId, $this->apiKey);
    }


}
