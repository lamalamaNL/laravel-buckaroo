<?php

namespace LamaLama\Buckaroo;

class Pay
{
    public function create($params)
    {
        $postArray = [
            "Currency" => "EUR",
            "AmountDebit" => 10.00,
            "Invoice" => "testinvoice 123",
            "Services" => [
                "ServiceList" => [
                    [
                        "Action" => "Pay",
                        "Name" => "ideal",
                        "Parameters" => [
                            [
                                "Name" => "issuer",
                                "Value" => "ABNANL2A"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $post = json_encode($postArray);

        echo $post . '<br><br>';

        $md5  = md5($post, true);
        $post = base64_encode($md5);

        echo '<b>MD5 from json</b> ' . $md5 . '<br><br>';
        echo '<b>base64 from MD5</b> ' . $post . '<br><br>';

        $websiteKey = 'WEBSITE_KEY';
        $uri        = strtolower(urlencode('testcheckout.buckaroo.nl/json/Transaction'));
        $nonce      = 'nonce_' . rand(0000000, 9999999);
        $time       = time();

        $hmac       = $websiteKey . 'POST' . $uri . $time . $nonce . $post;
        $s          = hash_hmac('sha256', $hmac, 'Secret Key', true);
        $hmac       = base64_encode($s);

        echo ("hmac " . $websiteKey . ':' . $hmac . ':' . $nonce . ':' . $time);
    }
}
