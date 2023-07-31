<?php

namespace Salesforce;

class ApiFunctions
{
    public function baseUrl(): string
    {
        return 'https://login.salesforce.com';
    }

    // APP_URL
    // SALESFORCE_CALLBACK_URI
    public function getRedirectUri(): string
    {
        $domain = env('APP_URL');
        $callbackUri = env('SALESFORCE_CALLBACK_URI');

        return "{$domain}{$callbackUri}";
    }


    // SALESFORCE_CLIENT_ID
    public function getClientId()
    {
        return env('SALESFORCE_CLIENT_ID');
    }

    // SALESFORCE_CLIENT_SECRET
    public function getClientSecret()
    {
        return env('SALESFORCE_CLIENT_SECRET');
    }

    public function getAuthUrl()
    {
        return "{$this->baseUrl()}/services/oauth2/authorize";
    }

    public function getTokenUrl()
    {
        return "{$this->baseUrl()}/services/oauth2/token";
    }
}
