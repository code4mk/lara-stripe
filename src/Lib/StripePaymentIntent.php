<?php

namespace Code4mk\LaraStripe\Lib;

/**
 * @author    @code4mk <hiremostafa@gmail.com>
 * @author    @kawsarsoft <with@kawsarsoft.com>
 * @copyright Kawsar Soft. (http://kawsarsoft.com)
 */

use Config;
use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Balance;
use Stripe\PaymentIntent;

class StripePaymentIntent
{
    /**
     * secret key
     *
     * @var string
     */
    private $secretKey;

    public function __construct()
    {
        if (config::get('lara-stripe.driver') === 'config') {
            $this->secretKey = config::get('lara-stripe.secret_key');
        }
    }

    /**
     * Set secret key
     *
     * @param  array  $data
     * @return $this
     */
    public function setup($data)
    {
        if ($data['secret_key']) {
            $this->secretKey = $data['secret_key'];
        }

        return $this;
    }

    /**
     * Get balance
     *
     * @return object
     */
    public function create()
    {
        try {
            Stripe::setApiKey($this->secretKey);

            return PaymentIntent::create([
                'amount' => 1000,
                'currency' => 'usd',
            ]);
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }
}
