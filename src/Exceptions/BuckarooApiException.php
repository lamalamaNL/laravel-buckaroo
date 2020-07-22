<?php

namespace LamaLama\LaravelBuckaroo\Exceptions;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Throwable;

class BuckarooApiException extends \Exception
{
    protected $statuscode = 0;
    protected $apiResponseBody = 'not set';

    /**
     * BuckarooApiException constructor.
     * @param $statuscode
     */
    public function __construct(string $message = "Unsucccesfull response from PSP", int $code = 5000, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    public static function createFromException(RequestException $exception) : BuckarooApiException
    {
        $response = $exception->getResponse();

        $exception = new static();
        $exception->statuscode = $response ? $response->getStatusCode() : 500;
        $exception->apiResponseBody = $response ? (string) $response->getBody() : 'no response found';

        return $exception;
    }

    public function toResponse() : JsonResponse
    {
        $data = [
            'message' => $this->getMessage(),
            'psp_api_statuscode' => $this->statuscode,
        ];
        if (Config::get('app.debug')) {
            $data['response_from_buckaroo'] = $this->apiResponseBody;
        }
        $response = new JsonResponse($data, $this->statuscode);

        return $response;
    }

    public function render()
    {
        return $this->toResponse();
    }

    /**
     * @param string $apiResponseBody
     */
    public function setApiResponseBody($apiResponseBody) : BuckarooApiException
    {
        $this->apiResponseBody = $apiResponseBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiResponseBody()
    {
        return $this->apiResponseBody;
    }

    /**
     * @return int
     */
    public function getStatuscode(): int
    {
        return $this->statuscode;
    }


    public function setStatuscode(int $statuscode): BuckarooApiException
    {
        $this->statuscode = $statuscode;

        return $this;
    }
}
