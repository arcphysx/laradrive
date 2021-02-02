<?php

namespace Arcphysx\Laradrive\Modules\Singleton;

use Arcphysx\Laradrive\Laradrive;
use Arcphysx\Laradrive\Modules\Contract\HttpClientModuleContract;
use Arcphysx\Laradrive\Modules\Wrapper\ResponseWrapper;

class Permissions implements HttpClientModuleContract
{
    private static $INSTANCE = null;

    private function __construct(){
        //
    }

    public static function _get()
    {
        if(self::$INSTANCE == null){
            self::$INSTANCE = new Permissions();
        }
        return self::$INSTANCE;
    }

    public function list($fileOrFolderId=null)
    {
        $response = Laradrive::httpClient()->get("files/$fileOrFolderId/permissions");

        return ResponseWrapper::parse($response);
    }

    public function get($fileOrFolderId, $permissionId)
    {
        $response = Laradrive::httpClient()->get("files/$fileOrFolderId/permissions/$permissionId");

        return ResponseWrapper::parse($response);
    }

    public function delete($fileOrFolderId, $permissionId)
    {
        $response = Laradrive::httpClient()->delete("files/$fileOrFolderId/permissions/$permissionId");

        return ResponseWrapper::parse($response);
    }

    public function create($fileOrFolderId, $role, $type)
    {
        // https://developers.google.com/drive/api/v3/reference/permissions/create
        $response = Laradrive::httpClient()->post("files/$fileOrFolderId/permissions", [
            'json' => [
                'role' => $role,
                'type' => $type,
            ]
        ]);

        return ResponseWrapper::parse($response);
    }
}