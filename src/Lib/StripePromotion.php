<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;

class StripePromotion
{
    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    private $stripe;
    private $coupon;
    private $max_redemptions = '';

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }

    public function couponId($id)
    {
        $this->coupon = $id;
        return $this;
    }

    public function maxRedem($data)
    {
        $this->max_redemptions = $data;
    }

    /**
     * Create promotion & retrieve data
     *
     * @return object
     */
    public function create()
    {
        try {
            $promotionData = [
                'coupon' => $this->coupon
            ];
            
            if ($this->max_redemptions !== '') {
                $promotionData['max_redemptions'] = $this->max_redemptions;
            }

            $promotion = $this->stripe->promotionCodes->create($promotionData);
            return $promotion;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function retrieve($id)
    {
        try {
            $promotion = $this->stripe->promotionCodes->retrieve($id);
            return $promotion;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function deactive($id)
    {
        try {
            $promotion = $this->stripe->promotionCodes->update($id,[
                'active' => false
            ]);
            return $promotion;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function active($id)
    {
        try {
            $promotion = $this->stripe->promotionCodes->update($id,[
                'active' => true
            ]);
            return $promotion;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function lists()
    {
        try {
            $coupon = $this->stripe->promotionCodes->all();
            return $coupon;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }
}
