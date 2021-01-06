<?php

namespace Zareismail\StripeCheckout;

use Laravel\Nova\Fields\Field;

class StripeCheckout extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'stripe-checkout'; 

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
    	parent::__construct($name, function() {}); 
    	$this->amount(0);
    	$this->params([]);
    	$this->key(strval(config('strip.publishable_key')));
    	$this->currency(config('nova.currency', 'USD'));
    }

    /**
     * Set the checkout endpoint url.
     * 
     * @param  string $path 
     * @return $this       
     */
    public function endpoint(string $path)
    {
    	return $this->withMeta([
    		'sessionUrl' => $path
    	]);
    } 

    /**
     * Set the checkout endpoint params.
     * 
     * @param  array $params 
     * @return $this       
     */
    public function params(array $params)
    {
    	return $this->withMeta([
    		'params' => $params
    	]);
    }

    /**
     * Set the checkout amount.
     * 
     * @param  float $amount 
     * @return $this       
     */
    public function amount(float $amount)
    {
    	return $this->withMeta([
    		'amount' => $amount
    	]);
    } 

    /**
     * Set the checkout currency.
     * 
     * @param  float $currency 
     * @return $this       
     */
    public function currency(string $currency)
    {
    	return $this->withMeta([
    		'currency' => $currency
    	]);
    }   

    /**
     * Set the stripe key.
     * 
     * @param  string $key 
     * @return $this       
     */
    public function key(string $key)
    {
    	return $this->withMeta([
    		'key' => $key
    	]);
    }

    /**
     * Determine fi the user can set custom amount.
     * 
     * @param  string $key 
     * @return $this       
     */
    public function customAmount(bool $custom = true)
    {
    	return $this->withMeta([
    		'custom' => $custom
    	]);
    }
}
