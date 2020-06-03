<?php

namespace LamaLama\Buckaroo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Connection
{
    /**
     * $apiEndpoint
     * @var string
     */
    protected $apiEndpoint;

    /**
     * $apiKey
     * @var string
     */
    protected $apiKey;

    /**
     * $apiSecret
     * @var string
     */
    protected $apiSecret;

    /**
     * $httpClient
     * @var GuzzleHttp\Client
     */
    public $httpClient;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->apiEndpoint = env('BUCKAROO_API_ENDPOINT');
        $this->apiKey = env('BUCKAROO_API_KEY');
        $this->apiSecret = env('BUCKAROO_API_SECRET');

        $this->httpClient = new Client([
            'base_uri' => $this->apiEndpoint,
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json'
            ],
            'auth' => [$this->apiKey, $this->apiSecret]
        ]);
    }
}

/**
 * Create env function for use outside Laravel
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return getenv($key, $default = null);
    }
}
