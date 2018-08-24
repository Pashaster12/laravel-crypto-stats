# laravel-crypto-stats

[![StyleCI](https://github.styleci.io/repos/145722897/shield)](https://styleci.io/repos/145722897)
[![Build Status](https://scrutinizer-ci.com/g/Pashaster12/laravel-crypto-stats/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Pashaster12/laravel-crypto-stats/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Pashaster12/laravel-crypto-stats/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Pashaster12/laravel-crypto-stats/?branch=master)
[![License](https://poser.pugx.org/pashaster12/laravel-crypto-stats/license.svg)](https://packagist.org/packages/pashaster12/laravel-crypto-stats)
[![Total Downloads](https://poser.pugx.org/pashaster12/laravel-crypto-stats/downloads.svg)](https://packagist.org/packages/pashaster12/laravel-crypto-stats)
[![Latest Stable Version](http://img.shields.io/packagist/v/pashaster12/laravel-crypto-stats.svg)](https://packagist.org/packages/pashaster12/laravel-crypto-stats)
<span class="badge-ehereum"><a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=0xf55b7BD86bc72b08427E7b5748E1eDD05f1AC8bd" title="Donate once-off to this project using Ethereum"><img src="https://img.shields.io/badge/ethereum-donate-blue.svg" alt="Ethereum donate button" /></a></span>

This is a Laravel 5 package allows to get the cryptocyrrency wallets information. You can use it for monitoring wallets' information in different Laravel applications, for example, [like this](https://github.com/Pashaster12/cryptoStatsApp).

It is a PHP wrapper under the https://chain.so and https://etherscan.io APIs.

### Docs

* [Features list](#features-list)
* [Installation](#installation)
* [Configuration](#configuration)
* [Code examples](#code-examples)
* [Package code structure](#package-code-structure)
* [Information for contributors](#information-for-contributors)
* [Futher plans](#futher-plans)
* [Donations](#donations)

## Features list

Package provides the following features:

- Validation cryptocurrency wallet address.
- Get the current cryptocurrency wallet balance.
- Generate links to block explorers where you can show the information about the cryptocurrency wallet.

Supported cryptocurrencies:

- ETH
- BTC
- LTC
- DOGE
- DASH
- ZEC

## Installation

Installations steps for the laravel-crypto-stats Laravel package are standard for all composer packages.

Add the latest package version in your composer.json by executing the command:

```bash
composer require pashaster12/laravel-crypto-stats
```

Or you can add it to your composer.json manually with the next `require` section edits:

```
"require": {
    ...
    "pashaster12/laravel-crypto-stats": "*",
    ...
},
```

And then just run the following command in your project's root folder in terminal for updating the dependencies of your project and re-generating the composer autoload files:

```bash
composer update
```

Good! :simple_smile: It's time to recognize the package code in your Laravel application. For this do the following steps:

### Laravel < 5.5

Register the provider directly in your app configuration file config/app.php `config/app.php` in the `providers` section:

```php
'providers' => [
    // ...

	  LaravelCryptoStats\LaravelCryptoStatsProvider::class,
]
```

Add the facade aliases in the same file in the `aliases` section:

```php
'aliases' => [
	  ...
    'CryptoStat'      => LaravelCryptoStats\LaravelCryptoStatsFacade::class,
]
```

### Laravel >= 5.5

For Laravel versions more than 5.5 there is a package `Service Provider` and `Facade` auto-detecting mechanizm in the framework. And the needed package settings were made for its realization, so you do not have to add `Service Provider` and `Facade` alias to the config `config/app.php` manually.

## Configuration

After the package installation into your Laravel app you may move its config file to your app's `config` directory for tuther customization with the following command:

```bash
php artisan vendor:publish --provider=LaravelCryptoStats\LaravelCryptoStatsProvider
```

At the current moment package config file which named `laravel_crypto_stats.php` consists of the following parameters:

```php
/*
 * Default cryptocurrencies which user want to use in the application
 */
'currencies' => [
    'ETH',
    'LTC',
    'BTC',
],
        
 /*
 * API key for Etherscan connection
 */
'etherscan_api_key' => env('ETHERSCAN_API_KEY'),
```

The `currencies` array consists of the cryptocurrencies which are available by default. You can use them for viewing them at your application's UI, for example.

> Important! `currencies` config values must be fit to the list of the package supported currencies, owervise you'll got the exception.

The `etherscan_api_key` parameter consists of the API key for accessing the `etherscan.io` API. It's just a link to the `ETHERSCAN_API_KEY` variable in the `.env` file. So create it and move the needed value there or place it in the `etherscan_api_key` config directly.

## Code examples

> For using package methods in your Laravel application code you should firstly include it in your class with the next code:

```php
use CryptoStat;
```

### Available package's methods usage examples:

```php
// get the list of the default currencies
$currencies = CryptoStat::getCurrencies(); //array in format [ 'ETH', 'BTC', ... ]

// validate the cryptocurrency wallet address
CryptoStat::setCurrency($this->currency);
$is_valid = CryptoStat::validateAddress($value); //bool

// get the cryptocurrency wallet balance
// method returns float number with the 8 decimal digits (chain.so API response format)
CryptoStat::setCurrency($wallet->currency);
$new_balance = CryptoStat::getBalance($wallet->address); //float

// generate links to block explorers
CryptoStat::setCurrency($wallet['currency']);
$block_explorer_link = CryptoStat::getBlockExplorerLink($wallet['address']); //string
```

## Package code structure

As noticed below, package is a Laravel 5 PHP wrapper for these APIs which allows to get different information about the cryptocurrency wallets:

-  https://chain.so
-  https://etherscan.io

So, there are API connectors classes in the `src\Connectors` folder which extends the abstract class `AbstractConnector`. So, if you want to add the new methods, write them into the `AbstractConnector` firstly.

If you want to add the new API connector, add the corresponging class to the `src\Connectors` folder and reorder all the abstract methods from the `AbstractConnector`.

Also you should inject them into the Laravel `Service Container` in the `LaravelCryptoStatsServiceProvider` and tagged them with the `laravel-crypto-stats.connectors` tag for API connector auto-checking in the `register` method. Also dublicate them in the `provides` method, because the `LaravelCryptoStatsServiceProvider` is `deferred`.

All general classes which needed for package's main classeswork locate in the `src\Services` folder.

## Information for contributors

Package is open for contributing. If you want to become the contributor, send your pull requets with code changes and write issues with your propositions and bug lists.

## Futher plans

In the future the following features will planned and would be added:

- Feature and unit tests
- New API connectors and methods

## Donations

This package has been made with a lots of unsleeped nights and cups of coffee. So, I think, it would be fair to treat me a coffee if my work proved to be of use to you :blush:

You can show your respect by sending **Ethereum** to this address: `0xf55b7BD86bc72b08427E7b5748E1eDD05f1AC8bd`

Or you can use `WebMoney` transactions to these wallets:

- **WMR (RUB currency)**: `R192612541367`
- **WMZ (USD currency)**: `Z174150497388`

:heart: Thank you!
