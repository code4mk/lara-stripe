<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;
use Code4mk\LaraStripe\Lib\StripeProducts;

class StripePrices
{
    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * Set currency for plan.
     *
     * @var string
     */
    private $currency;

    /**
     * plan billing recurring interval
     *
     * @var string
     */
    private $interval;

    /**
     * Plan price.
     *
     * @var int|float
     */
    private $amount;

    /**
     * Plan products
     *
     * @var array
     */
    private $product = [];

    /**
     * Plan extra properties
     *
     * @var array
     */
    private $extra = [];

    /**
     * Trail day
     *
     * @var int
     */
    private $trial_time;

    /**
     * Stripe instance.
     */
    public $stripe;

    /**
     * A brief description of the plan, hidden from customers.
     */
    private $nickname;
    private $intervalCount = '';

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }

    /**
     * Plan products
     *
     * @param array $data
     * @return $this
     */
    public function product($data)
    {
        $this->product = $data;
        return $this;
    }

    /**
     * Plan price
     *
     * @param int|float $amount
     * @return $this
     */
    public function amount($amount)
    {
        $this->amount = round($amount, 2) * 100;
        return $this;
    }

    /**
     * Plan recurring interval
     *
     * @param string $type [day,week,month,year]
     * @return $this
     */
    public function interval($type, $count='')
    {
        $this->interval = $type;

        if ($count !== '') {
            $this->intervalCount = $count;
        }

        return $this;
    }

    /**
     * Plan currency
     *
     * @param string $currency
     * @return $this
     */
    public function currency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Plan extra properties.
     *
     * @param array $data associate array
     * @return $this
     */
    public function extra($data)
    {
        $this->extra = $data;
        return $this;
    }

    /**
     * Plan trial time (day).
     *
     * @param int $day
     * @return $this
     */
    public function trial($day)
    {
        $this->trial_time = $day;
        return $this;
    }

    /**
     * Plan description.
     * 
     * @param string $data.
     * @return $this
     */
    public function description($data)
    {
        if ($data !='') {
            $this->nickname = $data;
        }
        return $this;
    }

    /**
     * Create plan & retrive data.
     *
     * @return object
     */
    public function createPrice()
    {
        $recuringData = [
            'interval' => $this->interval,
        ];

        if ($this->intervalCount) {
            $recuringData['interval_count'] = $this->intervalCount;
        }

        $priceData = [
            'unit_amount' => $this->amount,
            'currency' => $this->currency,
            'recurring' => $recuringData,
            'product' => $this->product,
        ];

        if ($this->nickname != '') {
            $priceData['nickname'] = $this->nickname;
        }

        try {
            $price = $this->stripe->prices->create($priceData);
            return $price;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieve a plan with $id.
     *
     * @param string $id
     * @return object
     */
    public function retrieve($id)
    {
        try {
            $price = $this->stripe->prices->retrieve($id);
            return $price;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Active a plan
     *
     * @param string $id
     * @return object
     */
    public function active($id)
    {
        try {
            $price = $this->stripe->prices->update(
                $id,
                ['active' => true]
            );
            return $price;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Deactive a plan.
     *
     * @param string $id
     * @return $this
     */
    public function deactive($id)
    {
        try {
            $price = $this->stripe->prices->update(
                $id,
                ['active' => false]
            );
            return $price;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Delete a plan and same time delete product.
     *
     * @param string $id
     * @return object
     */
    public function delete($id)
    {
        try {
            $price = $this->stripe->prices->retrieve($id);
            $priceProduct = $price->product;
            
            
            
            // $product = new StripeProducts();
            // $getProduct = $product->retrieve($planProduct);
            // $getProduct->delete();

            return $price;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrive all plans
     * 
     * @return array
     */
    public function lists()
    {
        $plans = $this->stripe->prices->all();
        return $plans;
    }
}
