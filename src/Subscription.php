<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Subscription extends Model
{
    protected $fillable = ['customer_id', 'amount', 'includeTransaction', 'startDate', 'ratePlanCode', 'configurationCode', 'code', 'SubscriptionGuid'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * @param string $rateplanCode
     * @param string $configCode
     * @return Subscription
     */
    public static function createByConfigKey(string $configKey, Customer $customer = null) : self
    {
        if (! isset(config('buckaroo.subscriptions')[$configKey])) {
            throw ValidationException::withMessages(['key' => 'This key is not found in the config']);
        }
        $config = config('buckaroo.subscriptions')[$configKey];
        if (! $config['key'] === $configKey) {
            throw ValidationException::withMessages(['key' => 'This key is not congigured']);
        }
        $sub = new static();
        $sub->setAttribute('ratePlanCode', $config['ratePlanCode']);
        $sub->setAttribute('configurationCode', $config['configurationCode']);
        if ($customer) {
            $sub->setAttribute('customer_id', $customer->id);
        }
        $sub->setAttribute('amount', $config['amount']);
        $sub->setAttribute('includeTransaction', '????');
        $sub->setAttribute('startDate', new \DateTime());
        $sub->setAttribute('code', Str::random(24));
        $sub->setAttribute('SubscriptionGuid', null);

        return $sub;
    }
}
