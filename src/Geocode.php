<?php

namespace Davidvandertuijn;

class Geocode
{
    /**
     * @see https://developers.google.com/maps/documentation/geocoding/intro
     */
    const URL = 'https://maps.googleapis.com/maps/api/geocode';

    /**
     * @var float
     */
    protected $fLatitude = 0.000000;

    /**
     * @var float
     */
    protected $fLongitude = 0.000000;

    /**
     * @var string
     */
    protected $sAddress = '';

    /**
     * @var string
     */
    protected $sKey = '';
    
    /**
     * Get Latitude.
     *
     * @return float $this->fLatitude
     */
    public function getLatitude(): float
    {
        return $this->fLatitude;
    }

    /**
     * Set Latitude.
     *
     * @param float $fLatitude
     */
    public function setLatitude(float $fLatitude)
    {
        $this->fLatitude = $fLatitude;
    }

    /**
     * Get Longitude.
     *
     * @return float $this->fLongitude
     */
    public function getLongitude(): float
    {
        return $this->fLongitude;
    }

    /**
     * Set Longitude.
     *
     * @param float $fLongitude
     */
    public function setLongitude(float $fLongitude): void
    {
        $this->fLongitude = $fLongitude;
    }

    /**
     * Get Address.
     *
     * @return string $this->sAddress
     */
    public function getAddress(): string
    {
        return $this->sAddress;
    }

    /**
     * Set Address.
     *
     * @param string $sAddress
     */
    public function setAddress(string $sAddress)
    {
        $this->sAddress = $sAddress;
    }

    /**
     * Get Key.
     *
     * @return string $this->sKey
     */
    public function getKey(): string
    {
        return $this->sKey;
    }

    /**
     * Set Key.
     *
     * @param string $sKey
     */
    public function setKey(string $sKey)
    {
        $this->sKey = $sKey;
    }
    
    /**
     * Request.
     *
     * @return bool
     */
    public function request(): bool
    {
        $sAddress = $this->getAddress();

        if (!$sAddress) {
            return false;
        }

        $sKey = $this->getKey();

        if (!$sKey) {
            return false;
        }
        
        $sUrl = self::URL.'/json?'.http_build_query([
            'address' => $sAddress,
            'sensor'  => 'false',
            'key' => $sKey
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $sResponse = curl_exec($ch);

        if ($sResponse === false) {
            return false;
        }

        $iHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($iHttpCode != 200) {
            return false;
        }

        $oResponse = json_decode($sResponse);

        if ($oResponse->status != 'OK') {
            return false;
        }

        $fLatitude = (float) $oResponse->results[0]->geometry->location->lat;
        $fLongitude = (float) $oResponse->results[0]->geometry->location->lng;

        $this->setLatitude($fLatitude);
        $this->setLongitude($fLongitude);

        return true;
    }
}
