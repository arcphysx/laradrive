<?php

namespace Arcphysx\Laradrive\Modules\Singleton;

use Arcphysx\Laradrive\Laradrive;
use Arcphysx\Laradrive\Modules\Contract\HttpClientModuleContract;
use Arcphysx\Laradrive\Modules\Wrapper\ResponseWrapper;
use Illuminate\Support\Facades\Hash;

class Files implements HttpClientModuleContract
{
    // https://stackoverflow.com/questions/60797372/how-can-i-get-share-link-from-google-drive-rest-api-v3

    private static $INSTANCE = null;

    private function __construct(){
        //
    }

    public static function _get()
    {
        if(self::$INSTANCE == null){
            self::$INSTANCE = new Files();
        }
        return self::$INSTANCE;
    }

    public function list($folderId=null, $query = null)
    {
        if(isset($folderId) && isset($query)){
            $query = "'$folderId' in parents and $query";
        }elseif(isset($folderId) && !isset($query)){
            $query = "'$folderId' in parents";
        }elseif(!isset($folderId) && isset($query)){
            $query = $query;
        }else{
            $query = "";
        }

        $response = Laradrive::httpClient()->get("files", [
            'query' => [
                'q' => $query
            ]
        ]);

        return ResponseWrapper::parse($response);
    }

    public function get($fileId)
    {
        $response = Laradrive::httpClient()->get("files/$fileId", [
            'query' => [
                'fields' => '*'
            ]
        ]);

        return ResponseWrapper::parse($response);
    }

    public function delete($fileId)
    {
        $response = Laradrive::httpClient()->delete("files/$fileId");

        return ResponseWrapper::parse($response);
    }

    public function upload($filename, $mimeType, $file, $parentId, $uploadType="multipart")
    {
        // https://stackoverflow.com/questions/60837047/how-can-i-upload-files-to-googledrive-in-multipart-type-by-using-guzzle

        $boundary = md5(mt_rand() . microtime());
        $metadata = json_encode([
            'name' => $filename,
            'mimeType' => $mimeType,
            'parents' => [ $parentId ],
        ]);

        $dataToUpload = "--{$boundary}\r\n";
        $dataToUpload .= "Content-Type: application/json\r\n\r\n";
        $dataToUpload .= "{$metadata}\r\n";
        $dataToUpload .= "--{$boundary}\r\n";
        $dataToUpload .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $dataToUpload .= base64_encode($file) . "\r\n";
        $dataToUpload .= "--{$boundary}--";

        $response = Laradrive::httpClient(true)->post("files", [
            'headers' => [
                'Content-Type' => 'multipart/related; boundary=' . $boundary,
            ],
            'query' => [
                'uploadType' => $uploadType
            ],
            'body' => $dataToUpload,
        ]);

        return ResponseWrapper::parse($response);
    }

    public function create($jsonBody)
    {
        $response = Laradrive::httpClient()->post("files", [
            'json' => $jsonBody,
        ]);

        return ResponseWrapper::parse($response);
    }

    public function copy($fileId, $destinationId)
    {
        $response = Laradrive::httpClient()->post("files/$fileId/copy", [
            'json' => [
                'parents' => [
                    $destinationId
                ]
            ],
        ]);

        return ResponseWrapper::parse($response);
    }
}
