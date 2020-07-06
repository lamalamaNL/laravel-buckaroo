<?php

namespace LamaLama\LaravelBuckaroo\Acknowledgments;

use Illuminate\Support\Str;
use LamaLama\LaravelBuckaroo\Api\ApiResponseBody;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;

class PaymentMethods implements AcknowledgmentInterface
{
    protected $availableMethods;

    /**
     * PaymentMethods constructor.
     * @param $availableMethods
     */
    public function __construct()
    {
        $methods = [];
        $configMethods = config('buckaroo.paymentMethods', []);
        foreach ($configMethods as $configMethod) {
            $methods[$configMethod] = [
                'key' => $configMethod,
                'name' => Str::title($configMethod),
                'options' => [],
            ];
        }
        $this->availableMethods = $methods;
    }


    public function toArray(): array
    {
        return $this->availableMethods;
    }


    public function parseIdealPaymentMethod(ApiResponseBody $buckarooResponse): void
    {
        try {
            $issuers = null;
            $actions = $buckarooResponse->getFromResponse('Actions', true);
            foreach ($actions as $action) {
                if (isset($action['Name']) && $action['Name'] === 'Pay') {
                    foreach ($action['RequestParameters'] as $requestParameter) {
                        if (isset($requestParameter['ListItemDescriptions'])) {
                            $issuers = $requestParameter['ListItemDescriptions'];

                            break;
                        }
                    }
                }
            }

            $result = [];
            foreach ($issuers as $issuer) {
                $result[$issuer['Value']] = [
                    'key' => $issuer['Value'],
                    'name' => $issuer['Description'],
                ];
            }
            $this->availableMethods['ideal']['options']['issuers'] = $result;
        } catch (\Exception $e) {
            throw new BuckarooApiException('Error parsing ideal issuers', 5001, $e);
        }
    }

    public function parseBuckarooResponse(): void
    {
        // TODO: Implement parseBuckarooResponse() method.
    }
}
