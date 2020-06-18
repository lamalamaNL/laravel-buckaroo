<?php namespace LamaLama\LaravelBuckaroo;

class Payment
{
    /**
     * @var string
     */
    protected $method;

    /**
     * Payment constructor.
     * @param string $method
     */
    public function __construct(string $method)
    {
        $this->method = $method;
    }
}
