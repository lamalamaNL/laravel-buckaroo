<?php

namespace LamaLama\Buckaroo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LamaLama\Buckaroo\Connection;
use LamaLama\Buckaroo\Subscriptions;

class BuckarooFactory
{
    /**
     * $debug
     * @var boolean
     */
    protected $debug;

    /**
     * $httpClient
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->debug = env('BUCKAROO_DEBUG');
        $this->httpClient = (new Connection())->httpClient;
    }

    /**
     * subscriptions
     * @return LamaLama\Buckaroo\Subscriptions
     */
    public function subscriptions()
    {
        return new Subscriptions();
    }

    /**
     * getRequest
     * @param  string $method
     * @param  string $body
     * @return xxx
     */
    public function getRequest(string $method, string $body = '')
    {
        return $this->apiRequest('GET', $method, $body);
    }

    /**
     * postRequest
     * @param  string $method
     * @param  string $body
     * @return xxx
     */
    public function postRequest(string $method, string $body = '')
    {
        return $this->apiRequest('POST', $method, $body);
    }

    /**
     * putRequest
     * @param  string $method
     * @param  string $body
     * @return xxx
     */
    public function putRequest(string $method, string $body = '')
    {
        return $this->apiRequest('PUT', $method, $body);
    }

    /**
     * patchRequest
     * @param  string $method
     * @param  string $body
     * @return xxx
     */
    public function patchRequest(string $method, string $body = '')
    {
        return $this->apiRequest('PATCH', $method, $body);
    }

    /**
     * deleteRequest
     * @param  string $method
     * @param  string $body
     * @return xxx
     */
    public function deleteRequest(string $method, string $body = '')
    {
        return $this->apiRequest('DELETE', $method, $body);
    }

    /**
     * apiRequest
     * @param  string $requestMethod
     * @param  string $method
     * @param  string $body
     * @return xxx
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
     * downloadRequest
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
