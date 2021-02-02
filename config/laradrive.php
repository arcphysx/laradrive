<?php

return [

    'base_url' => 'https://www.googleapis.com/drive/v3/',
    'base_upload_url' => 'https://www.googleapis.com/upload/drive/v3/',

    'api_key' => env('GOOGLE_DRIVE_API_KEY', 'your-google-api-key'),
    'token_storage_path' => env('GOOGLE_DRIVE_AUTH_TOKEN_STORAGE_PATH', 'app/google_auth_token.json'),
    'credential_storage_path' => env('GOOGLE_DRIVE_CREDENTIAL_STORAGE_PATH', 'credentials.json'),

];