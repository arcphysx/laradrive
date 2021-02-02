<?php

namespace Arcphysx\Laradrive\Modules\Singleton;

use Arcphysx\Laradrive\Laradrive;
use Arcphysx\Laradrive\Modules\Contract\HttpClientModuleContract;
use Arcphysx\Laradrive\Modules\Wrapper\ResponseWrapper;
use Google\Client;
use Google_Service_Drive;
use Illuminate\Support\Facades\File;

class GoogleClient implements HttpClientModuleContract
{
    
    private static $INSTANCE = null;
    private static $GOOGLE_CLIENT = null;

    private function __construct(){
        $this->initGoogleClient();
        $this->validateAuthToken();
    }

    public static function _get()
    {
        if(self::$INSTANCE == null){
            self::$INSTANCE = new GoogleClient();
        }
        return self::$INSTANCE;
    }

    private function initGoogleClient()
    {
        self::$GOOGLE_CLIENT = new Client();
        self::$GOOGLE_CLIENT->setApplicationName(env('APP_NAME', 'your-app-name'));

        // https://developers.google.com/drive/api/v3/about-auth
        self::$GOOGLE_CLIENT->setScopes(Google_Service_Drive::DRIVE);
        
        self::$GOOGLE_CLIENT->setAuthConfig(Laradrive::credentialPath());
        self::$GOOGLE_CLIENT->setAccessType('offline');
        self::$GOOGLE_CLIENT->setPrompt('select_account consent');
        self::$GOOGLE_CLIENT->setAccessToken($this->getAuthToken()->access_token);
        self::$GOOGLE_CLIENT->refreshToken($this->getAuthToken()->refresh_token);
        $this->writeAuthToken();
    }
    
    public function validateAuthToken()
    {
        // If there is no previous token or it's expired.
        if (self::$GOOGLE_CLIENT->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if (self::$GOOGLE_CLIENT->getRefreshToken()) {
                self::$GOOGLE_CLIENT->fetchAccessTokenWithRefreshToken(self::$GOOGLE_CLIENT->getRefreshToken());
                $this->writeAuthToken();
            } else {
                throw new \Exception("Failed to fetch new refresh token");
            }
        }
    }

    public function getCredentialInfo()
    {
        $json = File::get(Laradrive::credentialPath());
        $json = json_decode($json);
        return $json;
    }

    public function getAuthToken()
    {
        $json = File::get(Laradrive::authTokenPath());
        $json = json_decode($json);
        return $json;
    }

    public function writeAuthToken()
    {
        File::replace(Laradrive::authTokenPath(), json_encode(
            self::$GOOGLE_CLIENT->getAccessToken()
        ));
    }
}