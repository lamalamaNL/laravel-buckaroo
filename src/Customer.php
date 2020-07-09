<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Customer extends Model
{
    protected $fillable = ['email', 'phone', 'firstName', 'lastName', 'gender', 'birthDate', 'street', 'houseNumber', 'zipcode', 'city', 'country', 'culture', 'ip'];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function validateForSubscription()
    {
        $validator = Validator::make($this->getAttributes(), [
            'email' => 'required|email',
            'culture' => 'required|string',
            'lastName' => 'required|string',
            'street' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
