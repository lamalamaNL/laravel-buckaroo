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

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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


    public function setSuccessRedirectUrl(?string $url = null) : self
    {
        $this->setAttribute('redirect_success', $url);

        return $this;
    }

    public function setNoSuccessRedirectUrl(?string $url = null) : self
    {
        $this->setAttribute('redirect_failed', $url);

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
        foreach (config('buckaroo.paymentMethods') as $paymentType) {
            foreach ($paymentType as $methods) {
                if (! is_array($methods)) {
                    $paymentmethodOptions[] = $method;

                    continue;
                }
                foreach ($methods as $method) {
                    $paymentmethodOptions[] = $method;
                }
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
