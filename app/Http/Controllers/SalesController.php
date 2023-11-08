<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\HttpFunctions;

class SalesforceController extends Controller
{
    //
    public function index()
    {

        $http = new HttpFunctions();

        $resp = $http->auth();

        $body = $resp->getBody();

        while (!$body->eof()) {
            echo $body->read(1024);
        }

    }

    public function oauthCallback(Request $request)
    {

        $http = new HttpFunctions();

        $resp = $http->token($request->get('code'));

        dd($resp);
        /*
            array:9[
                "access_token" => ""
                "refresh_token" => ""
                "signature" => ""
                "scope" => ""
                "id_token" => ""
                "instance_url" => "https://"
                "id" => "https://"
                "token_type" => "Bearer"
                "issuead_at" => ""
            ]
        */



        //+++++++++++++++++++++++++++++create sobject++++++++++++++++++++++++++++++++++++
        $accessToken = $resp['access_token'];
        $instanceUrl = $resp['instance_url'];
        $objects = $http->get("{$instanceUrl}/service/data/v50.0/subjects", $accessToken);
        $objectName = $objects['sobjects'][1]['name'];
        $jsonData = [ 'Name' => 'New account' ];
        $http->post("{$instanceUrl}/service/data/v50.0/subjects/{$objectName}", $accessToken, $jsonData);


        //+++++++++++++++++++++++++++++Select sobject++++++++++++++++++++++++++++++++++++
        $accessToken = $resp['access_token'];
        $instanceUrl = $resp['instance_url'];
        //$resp = $http->get( url:"{$instanceUrl}/service/data/v50.0/subjects", $accessToken);
        $resp = $http->get("{$instanceUrl}/service/data/v50.0/subjects", $accessToken);
        dd($resp['sobjects'][0]);
        /*
            array:[
                "activateable" => ""
                "urls" => array:4{
                    "rowTemplate" => ""
                    "defaultVAlues" => ""
                    "describe" => ""
                    "sobject" => ""
                }
            ]
        */


            
        

        /*
        $url = new UrlRepository($resp['instance_url']);
        $accessToken = $resp['access_token'];

        $objects = $http->get($url->resources(), $accessToken);

        $objectName = $objects['sobjects'][1]['name'];

        $jsonData = [ 'Name' => 'This will be new name' ];
        // $http->post($url->objectResource($objectName), $accessToken, $jsonData);
        $id = '0010900000SH92pAAD';
        $objectRecordUrl = $url->objectRecord($objectName, $id);
        // $http->patch($objectRecordUrl, $accessToken, $jsonData);
        try {
            $http->delete($objectRecordUrl, $accessToken);
        } catch (ClientException $e) {
            dd('Record missing, code: '. $e->getCode());
        }

        $resp = $http->get($url->describe($objectName), $accessToken);

        $fieldList = array_map(function ($field) {
            return $field['name'];
        }, $resp['fields']);

        $data = $http->get($url->selectQuery($objectName, $fieldList), $accessToken);

        dd($data);

        //dd($objects['sobjects'][3]);
        */


    }

}
