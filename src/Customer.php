<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;

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
}
