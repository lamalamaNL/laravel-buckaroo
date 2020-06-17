<?php namespace LamaLama\LaravelBuckaroo;


class Subscription
{
    /**
     * @var \DateTime
     */
    protected $startdate;
    /**
     * @var string
     */
    protected $ratePlanCode;
    /**
     * @var string
     */
    protected $configurationCode;


    /**
     * Subscription constructor.
     * @param \DateTime $startdate
     * @param string $ratePlanCode
     * @param string $configurationCode
     */
    public function __construct(\DateTime $startdate, string $ratePlanCode, string $configurationCode)
    {
        $this->startdate = $startdate;
        $this->ratePlanCode = $ratePlanCode;
        $this->configurationCode = $configurationCode;
    }


}