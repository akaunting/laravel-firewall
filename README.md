# Web Application Firewall (WAF) package for Laravel

[![Version](https://img.shields.io/packagist/v/akaunting/firewall?label=release)](https://github.com/akaunting/firewall/releases)
![Downloads](https://img.shields.io/packagist/dt/akaunting/firewall)
![Tests](https://img.shields.io/github/workflow/status/akaunting/firewall/Tests?label=tests)
[![StyleCI](https://github.styleci.io/repos/197242392/shield?style=flat&branch=master)](https://styleci.io/repos/197242392)
[![Quality](https://img.shields.io/scrutinizer/quality/g/akaunting/firewall?label=quality)](https://scrutinizer-ci.com/g/akaunting/firewall)
[![License](https://img.shields.io/github/license/akaunting/firewall)](LICENSE.md)

This package intends to protect your Laravel app from different type of attacks such as XSS, SQLi, RFI, LFI, User Agent, and a lot more. It will also block repeated attacks and send notification via email and/or slack when attack is detected. Furthermore, it will log failed logins and block the IP after a number of attempts.

Note: Some middleware classes (i.e. Xss) are empty as the `Middleware` abstract class that they extend does all of the job, dynamically. In short, they all works ;)

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

Publish configuration, language, and migrations

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

Middlewares are already defined so should just add them to routes. The `firewall.all` middleware applies all the middlewares available in the `all_middleware` array of config file. 

```php
Route::group(['middleware' => 'firewall.all'], function () {
    Route::get('/', 'HomeController@index');
});
```

You can apply each middleware per route. For example, you can allow only whitelisted IPs to access admin:

```php
Route::group(['middleware' => 'firewall.whitelist'], function () {
    Route::get('/admin', 'AdminController@index');
});
```

Or you can get notified when anyone NOT in `whitelist` access admin, by adding it to the `inspections` config:

```php
Route::group(['middleware' => 'firewall.url'], function () {
    Route::get('/admin', 'AdminController@index');
});
```

### Custom views
You can customize and make a specific view for each violation

Available middlewares applicable to routes:

```php
firewall.all

firewall.agent
firewall.bot
firewall.geo
firewall.ip
firewall.lfi
firewall.php
firewall.referrer
firewall.rfi
firewall.session
firewall.sqli
firewall.swear
firewall.tarpit
firewall.url
firewall.whitelist
firewall.xss
```

You may also define `routes` for each middleware in `config/firewall.php` and apply that middleware or `firewall.all` at the top of all routes.

## Tarpit
A tarpit blocks for a certain amount of time. Each time there is an addition to the tarpit the penalty period is increased squared.
This tarpit has a setting for the grace_tries, which is the number of tries that the request is not penelized. After that the trapit starts blocking traffic.
The reason for putting a IP in a trapit could be wrong user credentials or other violations of the firewall

### Usage
Adding an IP to the tarpit
```
use Akaunting\Firewall\Models\Tarpit;
Tarpit::addTry(request()->ip());
```

Manual checking if the IP is blocked
```
use Akaunting\Firewall\Models\Tarpit;
if (Tarpit::isBlocked(request()->ip())){
    echo "BLOCKED"
}
```
OR
```
use Akaunting\Firewall\Models\Tarpit;
$blockedUntil = model::blockedUntil($this->ip()); // returns carbon object
if ($blockedUntil) {
    echo "BLOCKED until:" . $blockedUntil->format('d-m-Y i'); 
}
```

Removing from tarpit
```
use Akaunting\Firewall\Models\Tarpit;
Tarpit::remove($this->ip());
```

Response
```
    <div class="card-body">
        {{$try_again_in_mintues}} minutes
    </div>
```

Config
* grace_tries : Number of tries the user is not penelized
* penalty_seconds : The time of the penialization duration in seconds
* In the config you can specify the view that needs to be rendered on a blocking by the trapit

### Example
grace_tries = 3
penalty_seconds = 30

ip is blocked 3 times : no penalization because of grace period
ip is blocked 4 times : penalization for 1 block = 1*1*30 seconds = 30 seconds
ip is blocked 5 times : penalization for 2 block = 2*2*30 seconds = 2 minutes
ip is blocked 6 times : penalization for 6 block = 3*3*30 seconds = 4.5 min 
ip is blocked 7 times : penalization for 7 block = 4*4*30 seconds = 8 min



 

## Notifications

Firewall will send a notification as soon as an attack has been detected. Emails entered in `notifications.mail.to` config must be valid Laravel users in order to send notifications. Check out the Notifications documentation of Laravel for further information.

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
