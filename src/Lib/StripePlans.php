<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;
use Code4mk\LaraStripe\Lib\StripeProducts;

class StripePlans
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
    public function interval($type)
    {
        $this->interval = $type;
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
    public function createPlan()
    {
        $planData = [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'interval' => $this->interval,
            'product' => $this->product,
        ];

        if ($this->trial_time != '' ) {
            $planData['trial'] = $this->trial_time;
        }

        if ($this->nickname != '') {
            $planData['nickname'] = $this->nickname;
        }

        try {
            $plan = $this->stripe->plans->create($planData);
            return $plan;
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
            $plan = $this->stripe->plans->retrieve($id);
            return $plan;
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
            $plan = $this->stripe->plans->update(
                $id,
                ['active' => true]
            );
            return $plan;
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
            $plan = $this->stripe->plans->update(
                $id,
                ['active' => false]
            );
            return $plan;
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
            $plan = $this->stripe->plans->retrieve($id);
            $planProduct = $plan->product;
            $plan->delete();
            
            $product = new StripeProducts();
            $getProduct = $product->retrieve($planProduct);
            $getProduct->delete();

            return $plan;
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
        $plans = $this->stripe->plans->all();
        return $plans;
    }
}
