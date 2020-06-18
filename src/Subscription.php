<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['customer_id', 'includeTransaction', 'startDate', 'ratePlanCode', 'configurationCode', 'code', 'SubscriptionGuid'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
