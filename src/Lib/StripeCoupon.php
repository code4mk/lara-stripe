<?php

namespace Code4mk\LaraStripe\Lib;

use Illuminate\Support\Str;
use Stripe\StripeClient;

class StripeCoupon
{
    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * Currency when coupon amount is fixed
     *
     * @var string
     */
    private $currency = 'usd';

    /**
     * Coupon amount
     *
     * @var int|float
     */
    private $amount = 0;

    /**
     * Coupon amount type
     *
     * @var string per or fixed
     */
    private $type;

    /**
     * Coupon duration
     *
     * @var string once
     */
    private $duration;

    /**
     * Coupon name & coupon id
     *
     * @var string must be camel_case
     */
    private $name;

    /**
     * Coupon duration month
     *
     * @var int
     */
    private $durationMonth;

    private $stripe;

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }


    /**
     * Coupon amount
     *
     * @param int|float  $amount
     * @param string  $currency  fixed amount purpose
     * @param string  $type [fixed,percent]
     *
     * @source https://stripe.com/docs/api/coupons/object#coupon_object-amount_off
     *
     * @return $this
     */
    public function amount($amount, $type = 'fixed', $currency = 'usd')
    {
        if ($type === 'fixed') {
            $this->amount = round($amount, 2) * 100;
            $this->type = 'fixed';
            $this->currency = $currency;
        } else {
            $this->amount = $amount;
            $this->type = 'percent';
        }

        return $this;
    }

    /**
     * Coupon duration
     *
     * @param string $type  [forever,once,repeating]
     * @param int $month
     * @return $this
     */
    public function duration($type, $month = 1)
    {
        if ($type === 'repeating') {
            $this->durationMonth = $month;
        }

        $this->duration = $type;

        return $this;
    }

    /**
     * Coupon name & id
     *
     * @param string $name snake_case
     * @return $this
     */
    public function name($name)
    {
        $this->name = Str::snake($name);
        return $this;
    }

    /**
     * Create coupon & retrieve data
     *
     * @return object
     */
    public function create()
    {
        $couponData = [];

        if ($this->type === 'percent') {
            $couponData['percent_off'] = $this->amount;
        } else {
            $couponData['amount_off'] = $this->amount;
            $couponData['currency'] = $this->currency;
        }

        if ($this->name) {
            $couponData['name'] = $this->name;
        }

        if ($this->duration === 'forever' || $this->duration === 'once') {
            $couponData['duration'] = $this->duration;
        } else {
            $couponData['duration'] = $this->duration;
            $couponData['duration_in_months'] = $this->durationMonth;
        }

        try {
            $coupon = $this->stripe->coupons->create($couponData);
            return $coupon;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function retrieve($id)
    {
        try {
            $coupon = $this->stripe->coupons->retrieve($id);
            return $coupon;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function lists()
    {
        try {
            $coupon = $this->stripe->coupons->all();
            return $coupon;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    /**
     * Delete a coupon
     *
     * @param  string  $id coupon id
     * @return object
     */
    public function delete($id)
    {
        try {
            $coupon = $this->stripe->coupons->delete($id);
            return $coupon;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }
}
