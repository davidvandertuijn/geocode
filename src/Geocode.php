<?php

namespace Davidvandertuijn;

use Davidvandertuijn\Geocode\Exceptions\EmptyAddressException;
use Davidvandertuijn\Geocode\Exceptions\EmptyApiKeyException;
use Davidvandertuijn\Geocode\Exceptions\HttpCodeException;
use Davidvandertuijn\Geocode\Exceptions\InvalidRequestException;
use Davidvandertuijn\Geocode\Exceptions\OverDailyLimitException;
use Davidvandertuijn\Geocode\Exceptions\OverQueryLimitException;
use Davidvandertuijn\Geocode\Exceptions\RequestDeniedException;
use Davidvandertuijn\Geocode\Exceptions\UnkownErrorException;
use Davidvandertuijn\Geocode\Exceptions\ZeroResultsException;
use Exception;

/**
 * Geocode.
 * @see https://developers.google.com/maps/documentation/geocoding/intro
 */
class Geocode
{
    /**
     * @const string
     */
    const URL = 'https://maps.googleapis.com/maps/api/geocode';

    /**
     * @var string
     */
    public $address = '';

    /**
     * @var mixed
     */
    public $ch;

    /**
     * @var float
     */
    public $latitude = 0.000000;

    /**
     * @var float
     */
    public $longitude = 0.000000;

    /**
     * @var string
     */
    public $apiKey = '';

    /**
     * @var string
     */
    public $response = '';

    /**
     * Get Address.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Set Address.
     *
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * Get Api Key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set Api Key.
     *
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get Curl Handle.
     *
     * @return mixed
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * Set Curl Handle.
     *
     * @param mixed $ch
     */
    public function setCh($ch)
    {
        $this->ch = $ch;
    }

    /**
     * Get Latitude.
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Set Latitude.
     *
     * @param float $latitude
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get Longitude.
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Set Longitude.
     *
     * @param float $longitude
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get Response.
     *
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * Set Response.
     *
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * After.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\HttpCodeException
     * @throws \Davidvandertuijn\Geocode\Exceptions\InvalidRequestException
     * @throws \Davidvandertuijn\Geocode\Exceptions\OverDailyLimitException
     * @throws \Davidvandertuijn\Geocode\Exceptions\OverQueryLimitException
     * @throws \Davidvandertuijn\Geocode\Exceptions\RequestDeniedException
     * @throws \Davidvandertuijn\Geocode\Exceptions\UnkownErrorException
     * @throws \Davidvandertuijn\Geocode\Exceptions\ZeroResultsException
     */
    public function after()
    {
        // Check Failure.

        if ($this->getResponse() === false) {
            $this->curlError();
        }

        // Check HTTP Code.
        $this->checkHttpCode();

        // Check Status.
        $this->checkStatus();

        // Results.
        $this->results();
    }

    /**
     * Before.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\EmptyAddressException
     * @throws \Davidvandertuijn\Geocode\Exceptions\EmptyApiKeyException
     */
    public function before()
    {
        // Check Address.
        $this->checkAddress();

        // Check API Key.
        $this->checkApiKey();
    }

    /**
     * Check Address.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\EmptyAddressException
     */
    public function checkAddress()
    {
        if (empty($this->getAddress())) {
            throw new EmptyAddressException();
        }
    }

    /**
     * Check Api Key.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\EmptyApiKeyException
     */
    public function checkApiKey()
    {
        if (empty($this->getApiKey())) {
            throw new EmptyApiKeyException();
        }
    }

    /**
     * Check Http Code.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\HttpCodeException
     */
    public function checkHttpCode()
    {
        $httpCode = curl_getinfo($this->getCh(), CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            throw new HttpCodeException($httpCode);
        }
    }

    /**
     * Check Status.
     *
     * @throws \Davidvandertuijn\Geocode\Exceptions\InvalidRequestException
     * @throws \Davidvandertuijn\Geocode\Exceptions\OverDailyLimitException
     * @throws \Davidvandertuijn\Geocode\Exceptions\OverQueryLimitException
     * @throws \Davidvandertuijn\Geocode\Exceptions\RequestDeniedException
     * @throws \Davidvandertuijn\Geocode\Exceptions\UnkownErrorException
     * @throws \Davidvandertuijn\Geocode\Exceptions\ZeroResultsException
     */
    public function checkStatus()
    {
        // Decodes a JSON string.
        $response = json_decode($this->getResponse());

        switch ($response->status) {
            case "ZERO_RESULTS":
                throw new ZeroResultsException($response->error_message);
            case "OVER_DAILY_LIMIT":
                throw new OverDailyLimitException($response->error_message);
            case "OVER_QUERY_LIMIT":
                throw new OverQueryLimitException($response->error_message);
            case "REQUEST_DENIED":
                throw new RequestDeniedException($response->error_message);
            case "INVALID_REQUEST":
                throw new InvalidRequestException($response->error_message);
            case "UNKNOWN_ERROR":
                throw new UnkownErrorException($response->error_message);
        }
    }

    /**
     * cURL Error.
     *
     * @throws \Exception
     */
    public function curlError()
    {
        throw new Exception(curl_error($this->getCh()));
    }

    /**
     * cURL Setopt.
     */
    public function curlSetopt()
    {
        curl_setopt($this->getCh(), CURLOPT_URL, $this->curloptUrl());
        curl_setopt($this->getCh(), CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->getCh(), CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->getCh(), CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * Curlopt URL.
     */
    public function curloptUrl()
    {
        return self::URL.'/json?'.http_build_query([
                'address' => $this->getAddress(),
                'sensor' => 'false',
                'key' => $this->getApiKey()
            ]);
    }

    /**
     * Request.
     *
     * @return bool
     */
    public function request(): bool
    {
        // Before
        $this->before();

        // Initialize a cURL session.
        $this->setCh(curl_init());

        // Sets multiple options for a cURL session.
        $this->curlSetopt();

        // Perform a cURL session.
        $this->setResponse(curl_exec($this->getCh()));

        // After
        $this->after();

        return true;
    }

    /**
     * Results.
     */
    public function results()
    {
        // Decodes a JSON string.
        $response = json_decode($this->getResponse());

        // Latitude.

        $latitude = (float) $response->results[0]->geometry->location->lat;
        $this->setLatitude($latitude);

        // Longitude.

        $longitude = (float) $response->results[0]->geometry->location->lng;
        $this->setLongitude($longitude);
    }
}
