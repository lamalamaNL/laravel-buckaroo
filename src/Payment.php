<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['subscription_id', 'amount', 'currency', 'status', 'service', 'issuer', 'transactionId'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
