# Geocode

<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/davidvandertuijn/geocode"><img src="https://poser.pugx.org/davidvandertuijn/geocode/license.svg" alt="License"></a>
<img src="https://github.styleci.io/repos/93961811/shield?style=flat" alt="StyleCI">

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

$oGeocode->setAddress('Binnenhof 1A, 2511 CS, Den Haag, NL');

if ($oGeocode->request()) {
    $fLatitude = $oGeocode->getLatitude();
    $fLongitude = $oGeocode->getLongitude();
}
```
