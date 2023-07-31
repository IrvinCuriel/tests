<?php

namespace Salesforce;

class HttpFunctions
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    
    protected function createParams(array $params): array
    {
        return [
            'form_params' => $params,
            'stream' => true
        ];
    }

    /*
    public function get(string $url, string $token)
    {
        $resp = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'stream' => true,
        ]);

        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }

    public function post(string $url, string $token, array $data = [])
    {
        $resp = $this->client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'stream' => true,
        ]);

        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }

    public function patch(string $url, string $token, array $data = [])
    {
        $resp = $this->client->request('PATCH', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'stream' => true,
        ]);

        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }

    public function delete(string $url, string $token)
    {
        $this->client->request('DELETE', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'stream' => true,
        ]);
    }
    */

    public function auth()
    {
        $api = new ApiFunctions();
        $authUrl = $api->getAuthUrl();
        $params = $this->createParams([
            'client_id' => $api->getClientId(), //'https://login.salesforce.com/services/oauth2/authorize'
            'redirect_uri' => $api->getRedirectUri(), //http://localhost/oauth/callback
            'response_type' => 'code',
        ]);

        return $this->client->request('POST', $authUrl, $params);
    }


    public function token(string $code): array
    {
        $api = new ApiFunctions();
        $url = $api->getTokenUrl(); //'https://login.salesforce.com/services/oauth2/token'

        $params = $this->createParams([
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => $api->getClientId(),
            'client_secret' => $api->getClientSecret(),
            'redirect_uri' => $api->getRedirectUri(), //http://localhost/oauth/callback
        ]);

        $resp = $this->client->request('POST', $url, $params);
        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }

    
    protected function readResponseStream($body): string
    {
        $res = '';

        while (!$body->eof()) {
            $res .= $body->read(1024);
        }

        return $res;
    }





    /*  +++++++++++++++++++++++++++++Select sobject++++++++++++++++++++++++++++++++++++  */

    public function get(string $url, string $token)
    {
        $resp = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'stream' => true,
        ]);

        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }


    /*  +++++++++++++++++++++++++++++create sobject++++++++++++++++++++++++++++++++++++  */

    public function post(string $url, string $token, array $data = [])
    {
        $resp = $this->client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'stream' => true,
        ]);

        $resp = $this->readResponseStream($resp->getBody());

        return json_decode($resp, true);
    }
    

}
