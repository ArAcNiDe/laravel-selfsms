# SelfSMS

[![Latest Stable Version](https://img.shields.io/packagist/v/apiseo/laravel-selfsms.svg?style=flat-square)](https://packagist.org/packages/apiseo/laravel-selfsms) [![License](https://img.shields.io/badge/license-CeCILL--C-blue.svg?style=flat-square)](#license)

###SMS Notification in Laravel 5.1

SelfSMS is a simple package that allows you to send SMS notifications through specific providers.
For now, only one provider is implemented: "Free Mobile" (French carrier)

## Requirements

- PHP 5.5.9+

## Compatibility

- Laravel 5.1.*

## Installing

Use Composer to install it:

```
composer require apiseo/laravel-selfsms
```

## Installing on Laravel

Add the Service Provider and Facade alias to your `config/app.php` :

    'Apiseo\SelfSMS\Laravel\SelfSMSServiceProvider',

    'SelfSMS' => 'Apiseo\SelfSMS\Laravel\SelfSMSFacade',

Then publish the default configuration file into `config/selfsms.php` using this command :

    php artisan optimize
    php artisan vendor:publish

## Using It

#### Instantiate any provider directly (not recommanded)

```
use Apiseo\SelfSMS\FreeMobileSMSProvider;

$sms = new FreeMobileSMSProvider();

$sms->send('My message')
```

#### In Laravel you can use the IoC Container and the contract

```
$sms = app()->make('Apiseo\SelfSMS\SelfSMSProvider');

return $sms->send('My message')
```

#### Or Method Injection

```
use Apiseo\SelfSMS\SelfSMSProvider;

class NotificationController extends Controller {

	public function sendNotification(SelfSMSProvider $sms, $message)
	{
		return $sms->send($msg);
	}

}
```

#### Or the Facade

```
return \SelfSMS::send('My message')
```

## Be Fluent

You can use the fluent interface to configure the provider and send your message :

```
$sms = \Self::make();

$sms->withMessage('My text message');

$sms->send();
```

## Author

[Stéphane B.](http://twitter.com/aracnide)

## License

SelfSMS is licensed under the CeCILL-C License.
French version: [HTML](http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html) - [Text](http://www.cecill.info/licences/Licence_CeCILL-B_V1-fr.txt)
English version: [HTML](http://www.cecill.info/licences/Licence_CeCILL-C_V1-en.html) - [Text](http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt)

## Contributing

Feel free to contribute.
Any additional provider is welcome.
