<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;

class StripeCharge
{
    /**
     * card info num , exp date , exp year , cvc
     *
     * @var array
     */
    private $card = [];

    /**
     * source token (card)
     *
     * @var [type]
     */
    private $token = '';

    /**
     * Charge metadata
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Currency
     *
     * @var string length 3 and lowercase
     */
    private $currency = 'usd';

    /**
     * Charge description
     *
     * @var string
     */
    private $description = 'Stripe charge by lara-stripe';

    /**
     * Charge amount
     *
     * @var int|float
     */
    private $amount;

    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * Public key
     *
     * @var string
     */
    private $publicKey;

    /**
     * Payoption card or customer
     *
     * @var string
     */
    private $payOption;

    /**
     * Charge all data
     *
     * @var object
     */
    private $allOutput;

    /**
     * Exception error
     *
     * @var object
     */
    private $error;

    private $stripe;
    private $source = '';
    private $customer = '';

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }

    /**
     * set customer id
     * this method need for future charge as subcription
     *
     * @param  string  $token
     * @param string $type - customer
     * @return $this
     */
    public function chargeMethod($token, $type)
    {
        if ($type === 'customer') {
            $this->customer = $token;
        } else {
            $this->source = $token;
        }
        return $this;
    }

    /**
     * set charge amount
     *
     * @param  float|int|float  $amount
     * @return $this
     */
    public function amount($amount, $currency)
    {
        $this->amount = round($amount, 2) * 100;
        $this->currency = $currency;

        return $this;
    }

    /**
     * set some data which will be need for charge/payment
     *
     * ex: tnx_id,product_id,order_id or simliar
     *
     * @param  array  $data
     * @return $this
     */
    public function metadata($data)
    {
        $this->metadata = $data;

        return $this;
    }

    /**
     * set charge description
     *
     * @param  string  $text
     * @return $this
     */
    public function description($text)
    {
        $this->description = $text;

        return $this;
    }

    /**
     * create charge
     *
     * @return object charge objects.
     */
    public function create()
    {
        $chargeData = [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
        ];

        if ($this->source !== '') {
            $chargeData['source'] = $this->source;
        }

        if ($this->customer !== '') {
            $chargeData['customer'] = $this->customer;
        }

        if (count($this->metadata)) {
            $chargeData['metadata'] = $this->metadata;
        }

        try {
            $charge = $this->stripe->charges->create($chargeData);
            
            return $charge; 
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function retrieve($id)
    {
        try {
            $charge = $this->stripe->charges->retrieve($id);
            return $charge;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function lists()
    {
        try {
            $charges = $this->stripe->charges->all();
            return $charges;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }

    public function refund($chargeId, $amount = null)
    {
        $refundData = [
            'charge' => $chargeId,
        ];

        if ($amount) {
            $refundData['amount'] = round($amount, 2) * 100;
        }

        try {
            $refund = $this->stripe->refunds->create($refundData);
            return $refund;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage(), 'stripe' => $e->getJsonBody()['error']];
        }
    }
}
