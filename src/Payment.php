<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['customer_id', 'amount', 'currency', 'status', 'service', 'issuer', 'transactionId', 'transactionKey'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
