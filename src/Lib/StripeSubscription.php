<?php
namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;

class StripeSubscription
{
    /**
     * Secret key.
     *
     * @var string
     */
    private $secretKey;

    /**
     * Subscription Customer
     *
     * @var object
     */
    private $customer;

    /**
     * All data which properties of create the subscription.
     *
     * @var array
     */
    private $createSubscriptionData = [];

    /**
     * price which will be subcription.
     *
     * @var string
     */
    private $price;

    /**
     * Add others properties of create the subscription.
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Trial default from plan.
     *
     * @var bool
     */
    private $trialPlan = false;

    /**
     * How many trial day for subscription.
     * This will be override the plan trial day.
     *
     * @var int
     */
    private $trial;

    /**
     * Card source
     *
     * @var string
     */
    private $source;

    /**
     * Subcription with coupon.
     *
     * @var string
     */
    private $couponCode;

    private $stripe;

    private $promoCode;

    private $quantity = 1;

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }

    /**
     * set customer id which create by customer alias.
     *
     * @param  string  $id customer id
     * @return $this
     */
    public function customer($id)
    {
        $this->customer = $id;
        return $this;
    }

    /**
     * Price id
     *
     * @param  string  $id plan id
     * @return $this
     */
    public function priceId($id)
    {
        $this->price = $id;
        return $this;
    }

    /**
     * stripe subscription others properties which not declare this package.
     *
     * @param  array  $data
     * @return $this
     */
    public function metaData($data = [])
    {
        if (is_array($data)) {
            $this->metadata = $data;
        }
        return $this;
    }

    /**
     * Default trial time from plan.
     *
     * @return $this
     */
    public function trialPlan()
    {
        $this->trialPlan = true;

        return $this;
    }

    /**
     * Set subscription trial.
     * override the plan trial day.
     *
     * @param  int  $day
     * @return $this
     */
    public function trial($day)
    {
        $this->trial = $day;
        return $this;
    }

    public function quantity($data = 1)
    {
        $this->quantity = $data;
        return $this;
    }

    /**
     * set customer card source
     *
     * @param  string  $code
     * @return $this
     */
    public function source($code)
    {
        $this->source = $code;
        return $this;
    }

    /**
     * Coupon apply
     *
     * @param string $code
     * @return $this
     */
    public function coupon($code)
    {
        $this->couponCode = $code;
        return $this;
    }

    /**
     * Coupon apply
     *
     * @param string $code
     * @return $this
     */
    public function promo($code)
    {
        $this->promoCode = $code;
        return $this;
    }

    /**
     * Create & retreive all data.
     *
     * @return object
     */
    public function create()
    {
        $subscriptionsData = [
            'customer' => $this->customer,
            'items' => [
                ['price' => $this->price, 'quantity' => $this->quantity],
            ],
        ];

        // meta data associate array.
        if (count($this->metadata) > 0) {
            $subscriptionsData['metadata'] = $this->metadata;
        }

        // copuon
        if ($this->couponCode != '') {
            $subscriptionsData['coupon'] = $this->couponCode;
        }

        // promotion code
        if ($this->promoCode != '') {
            $subscriptionsData['promotion_code'] = $this->promoCode;
        }

        // default source
        if ($this->source != '') {
            $subscriptionsData['default_source'] = $this->source;
        }

        // trial in days
        if ($this->trial) {
            $subscriptionsData['trial_period_days'] = $this->trial;
        }

        try {
            $subscriptions = $this->stripe->subscriptions->create($subscriptionsData);
            return $subscriptions;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieve a subscription with id
     *
     * @param string $id
     * @return object
     */
    public function retrieve($id)
    {
        try {
            
            $subscription = $this->stripe->subscriptions->retrieve($id);
            return $subscription;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Cancel a subscription.
     *
     * @param  string  $id
     * @return object
     */
    public function cancel($id)
    {
        try {
            $subscription = $this->stripe->subscriptions->cancel($id);
            return $subscription;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }
}
