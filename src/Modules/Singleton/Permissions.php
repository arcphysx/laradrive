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

    public function create($fileOrFolderId, $role, $type, $additional=null)
    {
        // https://developers.google.com/drive/api/v3/reference/permissions/create
        $params = [
            'role' => $role,
            'type' => $type,
        ];
        if($additional !== null && is_array($additional)) $params = array_merge($params, $additional);
        $response = Laradrive::httpClient()->post("files/$fileOrFolderId/permissions", [
            'json' => $params
        ]);

        return ResponseWrapper::parse($response);
    }
}