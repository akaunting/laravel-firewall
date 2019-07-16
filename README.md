# Web Application Firewall (WAF) package for Laravel

[![Version](https://poser.pugx.org/akaunting/firewall/v/stable.svg)](https://github.com/akaunting/firewall/releases)
![Downloads](https://poser.pugx.org/akaunting/firewall/d/total.svg)
![Build Status](https://travis-ci.com/akaunting/firewall.svg)
[![StyleCI](https://styleci.io/repos/112121508/shield?style=flat&branch=master)](https://styleci.io/repos/112121508)
[![Quality](https://scrutinizer-ci.com/g/akaunting/firewall/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/akaunting/firewall)
[![License](https://poser.pugx.org/akaunting/firewall/license.svg)](LICENSE.md)

This package intends to protect your Laravel app from different type of attacks such as XSS, SQLi, RFI, LFI, and a lot more. It will also block repeated attacks and send notification via email and/or slack when attack is detected.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/firewall
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
Akaunting\Firewall\Provider::class,
```

### 3. Publish

Publish config file

```bash
php artisan vendor:publish --tag=firewall
```

### 4. Database

Create db tables

```bash
php artisan migrate
```

### 5. Configure

You can change the firewall settings of your app from `config/firewall.php` file

## Usage

Middlewares are already defined so should just add them to routes. The `firewall.all` middleware applies all the middlewares available in the `all_middlewares` array of config file. 

```php
Route::group(['middleware' => 'firewall.all'], function () {
    Route::get('/', 'HomeController@index');
});
```

You can also apply each middleware per route:

```php
Route::group(['middleware' => 'firewall.all'], function () {
    Route::get('/', 'HomeController@index');
});

// Only admins
Route::group(['middleware' => 'firewall.whitelist'], function () {
    Route::get('/admin', 'AdminController@index');
});
```

Available middlewares:

```php
firewall.all
firewall.ip
firewall.lfi
firewall.php
firewall.rfi
firewall.session
firewall.sqli
firewall.whitelist
firewall.xss
```

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
