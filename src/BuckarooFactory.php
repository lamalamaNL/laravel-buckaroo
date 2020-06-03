<?php

namespace LamaLama\Buckaroo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LamaLama\Buckaroo\Connection;
use LamaLama\Buckaroo\Subscriptions;

class BuckarooFactory
{
    /**
     * [$debug description]
     * @var [type]
     */
    protected $debug;

    /**
     * [$httpClient description]
     * @var [type]
     */
    protected $httpClient;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->debug = env('BUCKAROO_DEBUG');

        $connection = new Connection();
        $this->httpClient = $connection->httpClient;
    }

    /**
     * [invoices description]
     * @return [type] [description]
     */
    public function invoices()
    {
        return new Invoice();
    }

    /**
     * [labels description]
     * @return [type] [description]
     */
    public function labels()
    {
        return new Label();
    }

    /**
     * [parcels description]
     * @return [type] [description]
     */
    public function parcels()
    {
        return new Parcel();
    }

    /**
     * [parcelStatus description]
     * @return [type] [description]
     */
    public function parcelStatus()
    {
        return new ParcelStatu();
    }

    /**
     * [shippingMethods description]
     * @return [type] [description]
     */
    public function shippingMethods()
    {
        return new ShippingMethod();
    }

    /**
     * [users description]
     * @return [type] [description]
     */
    public function users()
    {
        return new User();
    }

    /**
     * [getRequest description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function getRequest(string $method, string $body = '')
    {
        return $this->apiRequest('GET', $method, $body);
    }

    /**
     * [postRequest description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function postRequest(string $method, string $body = '')
    {
        return $this->apiRequest('POST', $method, $body);
    }

    /**
     * [putRequest description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function putRequest(string $method, string $body = '')
    {
        return $this->apiRequest('PUT', $method, $body);
    }

    /**
     * [patchRequest description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function patchRequest(string $method, string $body = '')
    {
        return $this->apiRequest('PATCH', $method, $body);
    }

    /**
     * [deleteRequest description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function deleteRequest(string $method, string $body = '')
    {
        return $this->apiRequest('DELETE', $method, $body);
    }

    /**
     * [apiRequest description]
     * @param  string $requestMethod [description]
     * @param  string $method [description]
     * @param  string $body   [description]
     * @return array         [description]
     */
    public function apiRequest(string $requestMethod, string $method, string $body = '')
    {
        try {
            $response = $this->httpClient->{strtolower($requestMethod)}($method, [
                'body' => $body,
                'debug' => $this->debug
            ]);

            $body = $response->getBody();
            $contents = $body->getContents();
            $result = json_decode($contents);

            return $result;
        } catch (GuzzleException $e) {
            echo $e->getResponse()->getBody()->getContents();
        }
    }

    /**
     * [downloadRequest description]
     * @param  string $requestMethod [url]
     */
    public function downloadRequest(string $url)
    {
        try {
            $response = $this->httpClient->get($url, [
                'debug' => $this->debug
            ]);

            $body = $response->getBody();
            $contents = $body->getContents();

            return $contents;
        } catch (GuzzleException $e) {
            echo $e->getResponse()->getBody()->getContents();
        }
    }
}
