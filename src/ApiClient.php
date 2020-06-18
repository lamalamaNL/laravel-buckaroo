<?php namespace LamaLama\LaravelBuckaroo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Str;
use LamaLama\LaravelBuckaroo\Api\Action;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

class ApiClient
{
    protected $apiEndpoint;
    protected $apiKey;
    protected $apiSecret;
    protected $httpClient;
    protected $requestWithoutPayload = ['GET', 'DELETE'];

    public function __construct($mocks = null)
    {
        $this->apiEndpoint = config('buckaroo.endpoint');
        $this->apiKey = config('buckaroo.key');
        $this->apiSecret = config('buckaroo.secret');

        $config = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
            'debug' => false,
        ];
        if (! is_null($mocks)) {
            $config['handler'] = $this->createMockResponses($mocks);
        }


        $this->httpClient = new Client($config);
    }


    public function fetch(string $method, string $path, array $payload = []) : Action
    {
        $hasPayload = ! in_array(strtoupper($method), $this->requestWithoutPayload);
        $md5 = md5(json_encode($payload), true);
        $hmacPost = $hasPayload ? base64_encode($md5) : '';
        $uri = strtolower(urlencode($this->getUri($path, true)));
        $nonce = Str::random(16);
        $time = time();
        $hmac = $this->apiKey . strtoupper($method) . $uri . $time . $nonce . $hmacPost;
        $s = hash_hmac('sha256', $hmac, $this->apiSecret, true);
        $hmac = base64_encode($s);
        $authHeader = "hmac " . $this->apiKey . ':' . $hmac . ':' . $nonce . ':' . $time;

        $options = [
            'headers' => [
                'Authorization' => $authHeader,
            ],
        ];
        if ($hasPayload) {
            $options['json'] = $payload;
        }

        try {
            $response = $this->httpClient->request($method, $this->getUri($path), $options);
            $action = new Action((string) $response->getBody(), true);
        } catch (BuckarooApiException $e) {
            throw $e;
        } catch (RequestException $e) {
            throw BuckarooApiException::createFromException($e);
        }

        return $action;
    }

    private function getUri(string $url, bool $stripScheme = false): string
    {
        $uri = $this->stripSlashes($this->apiEndpoint) . "/" . $this->stripSlashes($url);
        if ($stripScheme) {
            $parts = explode('://', $uri);
            if (count($parts) > 0) {
                return $parts[1];
            }

            return $uri;
        }

        return $uri;
    }

    private function stripSlashes(string $uri): string
    {
        return trim(rtrim($uri, '/'), '/');
    }

    private function createMockResponses($mocks) : HandlerStack
    {
        $mock = new MockHandler($mocks);

        return HandlerStack::create($mock);
    }
}
