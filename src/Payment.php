<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;
use LamaLama\LaravelBuckaroo\Subscription;

class Payment extends Model
{
    protected $fillable = ['subscription_id', 'amount', 'currency', 'status', 'service', 'issuer', 'transactionId'];
    
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
