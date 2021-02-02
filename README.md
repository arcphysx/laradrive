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

You can publish the configuration file and assets by running:
 
```sh
$ php artisan vendor:publish --provider="Arcphysx\Laradrive\Providers\LaradriveServiceProvider"
```

After publishing a few new files to our application we need to reload them with the following command:

```sh
$ composer dump-autoload
```
