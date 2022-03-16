# Geocode

<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/license.svg" alt="License"></a>

![Geocode](https://cdn.davidvandertuijn.nl/github/geocode.png)

## Register an API Key

Your application's API key. This key identifies your application for purposes of quota management. <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Learn how to get a key.</a>

## Install

```
composer require davidvandertuijn/geocode
```
## Usage

```php
use Davidvandertuijn\Geocode;
```

**Request**

```php
$geocode = new Geocode();

$geocode->setApiKey(''); // Your application's API key.
$geocode->setAddress('Westblaak 180, 3012 KN, Rotterdam, NL');

if ($geocode->request()) {
    $latitude = $geocode->getLatitude(); // 51.9163212
    $longitude = $geocode->getLongitude(); // 4.475754
}
```
