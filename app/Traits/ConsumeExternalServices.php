<?php

namespace App\Traits;

trait ConsumeExternalServices {


    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonFile = false)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUri,
        ]);

        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method, $requestUrl, [
            $isJsonFile ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams,
        ]);

        $response = $response->getBody()->getContents();

        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }


        return $response;
    }

}