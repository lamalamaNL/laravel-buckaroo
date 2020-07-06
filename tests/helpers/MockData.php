<?php namespace LamaLama\LaravelBuckaroo\Tests\helpers;

class MockData
{
    public static $customerProps = [
            'email' => 'john_smith@lamalama.nl',
            'phone' => '06-555555555',
            'firstName' => 'john',
            'lastName' => 'smith',
            'gender' => 'male',
            'birthDate' => '01-01-2000',
            'street' => 'bara straat',
            'houseNumber' => '5',
            'zipcode' => '0000AA',
            'city' => 'Amsterdam',
            'culture' => 'nl-NL',
            'country' => 'NL',
            'ip' => '213.127.75.72',
        ];


    public static function getPaymentData($amount)
    {
        return [
            'amount' => $amount,
            'currency' => 'EUR',
            'status' => 'open',
            'service' => 'ideal',
            'issuer' => 'ABNANL2A',
            'transactionId' => null,
        ];
    }
}
