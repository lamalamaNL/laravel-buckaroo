<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class Payment extends Model
{
    protected $guarded = ['id'];

    /**
     * Payment constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttribute('currency', 'EUR');
        $this->setAttribute('status', 'open');
        parent::__construct($attributes);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    /**
     * @param float $amount
     * @return Payment
     */
    public function setAmount(float $amount) : self
    {
        $this->setAttribute('amount', $amount);

        return $this;
    }


    /**
     * @param string $paymentmethod
     * @param null $issuer
     * @return Payment
     */
    public function setPaymentmethod(string $paymentmethod, $issuer = null) : self
    {
        $this->setAttribute('paymentmethod', $paymentmethod);
        if ($issuer) {
            $this->setAttribute('payment_issuer', $issuer);
        }

        return $this;
    }

    public function validate()
    {
        $paymentmethodOptions = [];
        foreach (config('buckaroo.paymentMethods') as $methods) {
            foreach ($methods as $method) {
                $paymentmethodOptions[] = $method;
            }
        }
        $paymentmethodOptions = array_unique($paymentmethodOptions);
        $validator = Validator::make($this->getAttributes(), [
            'amount' => 'required|',
            'paymentmethod' => ['required', Rule::in($paymentmethodOptions)],
            'payment_issuer' => Rule::requiredIf($this->service === 'ideal'),
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
