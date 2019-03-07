<?php

namespace Davidvandertuijn;

class Geocode
{
    const URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected $fLatitude;
    protected $fLongitude;
    protected $sAddress;

    /**
     * Get Latitude.
     *
     * @return string $this->fLatitude
     */
    public function getLatitude()
    {
        return $this->fLatitude;
    }

    /**
     * Set Latitude.
     *
     * @param string $fLatitude
     */
    public function setLatitude($fLatitude)
    {
        $this->fLatitude = $fLatitude;
    }

    /**
     * Get Longitude.
     *
     * @return string $this->fLongitude
     */
    public function getLongitude()
    {
        return $this->fLongitude;
    }

    /**
     * Set Longitude.
     *
     * @param string $fLongitude
     */
    public function setLongitude($fLongitude)
    {
        $this->fLongitude = $fLongitude;
    }

    /**
     * Get Address.
     *
     * @return string $this->sAddress
     */
    public function getAddress()
    {
        return $this->sAddress;
    }

    /**
     * Set Address.
     *
     * @param string $sAddress
     */
    public function setAddress($sAddress)
    {
        $this->sAddress = $sAddress;
    }

    /**
     * Request.
     *
     * @return bool
     */
    public function request()
    {
        $sAddress = $this->getAddress();

        if (!$sAddress) {
            return false;
        }

        $sUrl = self::URL.'?'.http_build_query([
            'address' => $sAddress,
            'sensor'  => 'false',
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
