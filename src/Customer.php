<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;
use LamaLama\LaravelBuckaroo\Subscription;

class Customer extends Model
{
    protected $fillable = ['email', 'phone', 'firstName', 'lastName', 'gender', 'birthDate', 'street', 'houseNumber', 'zipcode', 'city', 'country'];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
