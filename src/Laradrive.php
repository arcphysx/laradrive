<?php

namespace Arcphysx\Laradrive;

use Arcphysx\Laradrive\Modules\Singleton\Files;
use Arcphysx\Laradrive\Modules\Singleton\GoogleClient;
use Arcphysx\Laradrive\Modules\Singleton\Permissions;
use GuzzleHttp\Client;
use Arcphysx\Laradrive\Modules\Singleton\RequestHandler;

class Laradrive
{

    static $GOOGLE_CLIENT = null;

    /**
     * The Base API URL.
     *
     * @return string
     */
    public static function baseUrl()
    {
        return config('laradrive.base_url');
    }

    /**
     * The Base Upload API URL.
     *
     * @return string
     */
    public static function baseUploadUrl()
    {
        return config('laradrive.base_upload_url');
    }

    /**
     * The Google API Auth Token Path.
     *
     * @return string
     */
    public static function authTokenPath()
    {
        return storage_path(config('laradrive.token_storage_path'));
    }

    /**
     * The Google API Credential Path.
     *
     * @return string
     */
    public static function credentialPath()
    {
        return storage_path(config('laradrive.credential_storage_path'));
    }

    /**
     * The Google API Client ID.
     *
     * @return string
     */
    public static function apiKey()
    {
        return config('laradrive.api_key');
    }

    /**
     * The Google API Client ID.
     *
     * @return string
     */
    public static function clientId()
    {
        return Laradrive::googleClient()->client_id;
    }

    /**
     * The Google API Client Secret.
     *
     * @return string
     */
    public static function clientSecret()
    {
        return Laradrive::googleClient()->client_secret;
    }

    /**
     * The Google API Refresh Token.
     *
     * @return string
     */
    public static function refreshToken()
    {
        return Laradrive::googleClient()->getAuthToken()->refresh_token;
    }

    /**
     * The Google API Refresh Token.
     *
     * @return string
     */
    public static function accessToken()
    {
        return Laradrive::googleClient()->getAuthToken()->access_token;
    }

    /**
     * The Google API Client Wrapper.
     *
     * @return string
     */
    public static function googleClient()
    {
        return GoogleClient::_get();
    }

    /**
     * The global Curl client.
     *
     * @return Client
     */
    public static function httpClient($upload=false)
    {   
        Laradrive::googleClient()->validateAuthToken();
        return new Client([
            'base_uri' => ($upload ? Laradrive::baseUploadUrl() : Laradrive::baseUrl()),
            'handler' => RequestHandler::_get()->handler()
        ]);
    }

    /**
     * The global Files singleton.
     *
     * @return Files
     */
    public static function files()
    {
        return Files::_get();
    }

    /**
     * The global Permissions singleton.
     *
     * @return Permissions
     */
    public static function permissions()
    {
        return Permissions::_get();
    }
}