<?php

namespace LamaLama\LaravelBuckaroo\Api;

use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

class Action
{
    protected $status = null;

    protected $failedStatuses = [490, 491, 492];
    protected $rawResponse = null;
    protected $throwExceptionAtParseError = false;


    public function __construct(string $rawResponse, bool $throwExceptionAtParseError = false)
    {
        $this->throwExceptionAtParseError = $throwExceptionAtParseError;
        $this->parseRawResponse($rawResponse);
    }

    public function parseRawResponse(string $rawResponse)
    {
        $this->rawResponse = json_decode($rawResponse, true);

        $nestedValue = 'Status.Code';
        if (isset($this->rawResponse['Transaction']['Status']['Code'])) {
            $nestedValue = 'Transaction.Status.Code';
        }
        $this->status = $this->getFromResponse($nestedValue . '.Code');
        if (in_array($this->status, $this->failedStatuses)) {
            $reason = $this->getFromResponse($nestedValue . '.Description', true);

            throw (new BuckarooApiException("Unsuccesfull PSP api call, status $this->status: $reason"))
                    ->setApiResponseBody($this->rawResponse)
                    ->setStatuscode($this->status);
        }
    }

    private function getFromResponse(string $key, $throwExceptionOverride = null)
    {
        try {
            return $this->getNestedValueFromArray($this->rawResponse, $key);
        } catch (\Exception $e) {
            if ($this->throwExceptionAtParseError) {
                if (! is_null($throwExceptionOverride) && $throwExceptionOverride === true) {
                    return null;
                }

                throw (new BuckarooApiException("Can not get $key from PSP response"))
                    ->setApiResponseBody($this->rawResponse);
            }
        }

        return null;
    }

    private function getNestedValueFromArray(array $object, string $key, $level = 0)
    {
        $keys = explode('.', $key);
        $result = $object[$keys[$level]];
        if ($level >= count($keys) - 1) {
            return $result;
        }

        return $this->getNestedValueFromArray($result, $key, $level + 1);
    }


    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return null
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }
}
