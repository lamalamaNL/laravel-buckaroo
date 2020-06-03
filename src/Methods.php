<?php

namespace LamaLama\Buckaroo;

class Methods
{
    /**
     * methods
     *
     * It is also possible to use an iDEAL transaction as a reference transaction
     * for recurring SEPA Direct Debit transactions. To do this, add the basic parameter
     * "StartRecurrent" with value "True" to your iDEAL API request
     *
     * @return array
     */
    public function methods()
    {
        return [
            'amex' => 'American Express',
            'ideal' => 'iDeal',
            'mastercard' => 'Mastercard',
            'paypal' => 'PayPal',
            'visa' => 'Visa',
        ];
    }

    /**
     * idealIssuers
     * @return array
     */
    public function idealIssuers()
    {
        return [
            'ABNANL2A' => 'ABN AMRO',
            'ASNBNL21' => 'ASN Bank',
            'INGBNL2A' => 'ING',
            'RABONL2U' => 'Rabobank',
            'SNSBNL2A' => 'SNS Bank',
            'RBRBNL21' => 'SNS Regio Bank',
            'TRIONL2U' => 'Triodos Bank',
            'FVLBNL22' => 'Van Lanschot',
            'KNABNL2H' => 'Knab',
            'BUNQNL2A' => 'Bunq',
            'MOYONL21' => 'Moneyou',
            'HANDNL2A' => 'Handelsbanken'
        ];
    }

}
