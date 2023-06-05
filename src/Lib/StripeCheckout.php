<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\StripeClient;

class StripeCheckout
{
    /**
     * Checkout Currency.
     *
     * @var string length 3 and lowercase
     */
    private $currency = 'usd';

    /**
     * Checkout description.
     *
     * @var string
     */
    private $description = 'Stripe payment checkout';

    /**
     * Checkout products data.
     *
     * @var array
     */
    private $products = [];

    /**
     * Secret key.
     *
     * @var string
     */
    private $secretKey;

    /**
     * Public key.
     *
     * @var string
     */
    private $publicKey;

    /**
     * Checkout success url.
     *
     * @var string
     */
    private $successURI;

    /**
     * Checkout cancel url.
     *
     * @var string
     */
    private $cancelURI;

    /**
     * Checkout ref ex: product id , payment id, card id similar.
     *
     * @var string
     */
    private $referenceKey = '';

    private $checkoutData = [];

    private $amount = 0;

    private $stripe;

    private $theTitle = 'Payment';

    private $theAdditionalInfo = [];

    public function __construct()
    {
        $this->currency = config('stripe.currency');
        $this->secretKey = config('stripe.secret_key');
        $this->publicKey = config('stripe.public_key');
        $this->successURI = config('stripe.success_url');
        $this->cancelURI = config('stripe.cancel_url');

        $this->stripe = new StripeClient($this->secretKey);
    }

    /**
     * Transaction id.
     *
     * @param $data.
     * @return $this
     */
    public function tnx($data)
    {
        $this->referenceKey = $data;
        $this->theDescription = $data;

        return $this;
    }

    /**
     * Payment title.
     *
     * @param $data.
     * @return $this
     */
    public function title($data)
    {
        $this->theTitle = $data;

        return $this;
    }

    /**
     * Additional data. associate array.
     *
     * @param  array  $data.
     * @return $this
     */
    public function additionalData($data)
    {
        $this->theAdditionalInfo = $data;

        return $this;
    }

    /**
     * Payment amount.
     *
     * @param $data.
     * @return $this
     */
    public function amount($data)
    {
        $this->amount = $data;

        return $this;
    }

    /**
     * Get session id and public key
     *
     * @return object sid and pkey
     */
    public function get()
    {
        try {
            $this->checkoutData['payment_method_types'] = ['card'];
            $this->checkoutData['success_url'] = $this->successURI.'?session_id={CHECKOUT_SESSION_ID}';
            $this->checkoutData['cancel_url'] = $this->cancelURI.'?session_id={CHECKOUT_SESSION_ID}';
            $this->checkoutData['client_reference_id'] = $this->referenceKey;
            $this->checkoutData['mode'] = 'payment';

            // Line items.
            $this->checkoutData['line_items'] = [[
                'price_data' => [
                    'currency' => $this->currency,
                    'unit_amount' => $this->amount * 100, // Amount in cents (400 USD * 100)
                    'product_data' => [
                        'name' => $this->theTitle],
                ],
                'quantity' => 1,
            ]];

            $this->checkoutData['metadata'] = $this->theAdditionalInfo;

            // Create session.
            $session = $this->stripe->checkout->sessions->create($this->checkoutData);

            $output = [
                'session_id' => $session->id,
                'public_key' => $this->publicKey,
                'checkout_url' => $session->url,
            ];

            return (object) $output;

        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieve session (checkout).
     *
     * @param  string  $sessionToken
     * @return object $infos
     */
    public function retrieve($sessionToken)
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionToken);

            return $session;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Checkout refund.
     *
     * Store payment_intent when checkout success in DB.
     *
     * @param  string  $payment_intent get from database
     * @return object
     */
    public function refund($payment_intent)
    {
        try {
            $paymentIntents = $this->stripe->paymentIntents->retrieve($payment_intent);

            $refund = $this->stripe->refunds->create([
                'charge' => $paymentIntents->charges->data[0]->id,
            ]);

            return $refund;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Session status.
     *
     * @param $sessionToken - The session token.
     */
    public function status($sessionToken)
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionToken);

            $paymentIntents = $this->stripe->paymentIntents->retrieve(
                $session->payment_intent
            );

            return (object) [
                'status' => $paymentIntents->status,
                'ref_id' => $session->client_reference_id,
                'sessions' => $session,
            ];

        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }
}
