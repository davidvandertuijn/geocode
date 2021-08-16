# Geocode

<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/license.svg" alt="License"></a>

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
$oGeocode = new Geocode();

$oGeocode->setKey(''); // Your application's API key.
$oGeocode->setAddress('Binnenhof 1A, 2511 CS, Den Haag, NL');

if ($oGeocode->request()) {
    $fLatitude = $oGeocode->getLatitude();
    $fLongitude = $oGeocode->getLongitude();
}
```
