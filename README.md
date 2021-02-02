# Laradrive
Google Drive REST API Wrapper For Laravel and Lumen

## Installation
You can install the package using composer
```sh
$ composer require arcphysx/laradrive
```
Then add the service provider to `config/app.php`. In Laravel versions 5.5 and beyond, this step can be skipped if package auto-discovery is enabled.

```php
'providers' => [
    ...
    Arcphysx\Laradrive\Providers\LaradriveServiceProvider::class
    ...
];
```

Run `php artisan laradrive:install` to run first setup

Then add some required value on your .env file
```
GOOGLE_DRIVE_API_KEY="<your-google-api-key-here>"
GOOGLE_DRIVE_AUTH_TOKEN_STORAGE_PATH="app/google_auth_token.json"
GOOGLE_DRIVE_CREDENTIAL_STORAGE_PATH="credentials.json"
```

By default, laradrive will use your `storage` to store your Google Authentication Info 

You can publish the configuration file and assets by running:
 
```sh
$ php artisan vendor:publish --provider="Arcphysx\Laradrive\Providers\LaradriveServiceProvider"
```

After publishing a few new files to our application we need to reload them with the following command:

```sh
$ composer dump-autoload
```
