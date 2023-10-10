<?php
namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;

class StripeCustomer
{
    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * Customer all data after create
     *
     * @var object
     */
    private $customer;

    /**
     * Customer metadata
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Customer  all data
     *
     * @var array
     */
    private $createCustomerData = [];

    private $stripe;
    private $name;
    private $email;

    public function __construct()
    {
        $this->secretKey = config('lara-stripe.secret_key');
        $this->stripe = new StripeClient($this->secretKey);
    }


    /**
     * set customer source (card) , email
     * https://stripe.com/docs/api/customers/create
     *
     * @param  array  $datas
     * @return $this
     */
    public function createl($datas)
    {
        foreach ($datas as $key => $data) {
            $this->createCustomerData[$key] = $data;
        }

        return $this;
    }

    /**
     * Set customer metadata
     *
     * @param  array  $data
     * @return $this
     */
    public function metadata($data)
    {
        $this->metadata = $data;
        return $this;
    }

    public function name($data) {
        $this->name = $data;
        return $this;
    }

    public function email($data) {
        $this->email = $data;
        return $this;
    }

    /**
     * Create customer and return customer data
     *
     * @return object
     */
    public function create()
    {
        $customerData = [];
        if ($this->name) {
            $customerData['name'] = $this->name;
        }

        if ($this->email) {
            $customerData['email'] = $this->email;
        }

        if ($this->metadata) {
            $customerData['metadata'] = $this->metadata;
        }

        try {
            $customer = $this->stripe->customers->create($customerData);
            return $customer;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrive customer with $cusIdid.
     *
     * @param  string  $id
     * @return object
     */
    public function retrieve($id)
    {
        try {
            $customer = $this->stripe->customers->retrieve($id);
            return $customer;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }

    }

    public function delete($id) {
        try {
            $customer = $this->stripe->customers->delete($id);
            return $customer;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    public function lists() {
        try {
            $customers = $this->stripe->customers->all();
            return $customers;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    public function cards($id)
    {
        try {
            $customer = $this->stripe->customers->retrieve($id);
            $sources = $customer->sources->data;
            $defaultCard = $customer->default_source;
            $cards = [];

            foreach ($sources as $key => $source) {
                if ($source->id === $defaultCard) {
                    $cards[$key] = ['cardId' => $source->id, 'last4' => $source->last4, 'brand' => $source->brand, 'customer' => $source->customer, 'isDefault' => true];
                } else {
                    $cards[$key] = ['cardId' => $source->id, 'last4' => $source->last4, 'brand' => $source->brand, 'customer' => $source->customer, 'isDefault' => false];
                }
            }
            
            return $cards;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    public function addCard($cusId, $cardToken, $max = 3)
    {
        try {
            if (count($this->cards($cusId)) <= $max - 1) {
                $customer = $this->stripe->customers->createSource($cusId, [
                    'source' => $cardToken,
                ]);
                return $customer;
            }

            return "You already exceed card quota {$max}";

        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    public function deleteCard($cusId, $cardToken)
    {
        try {
            if (count($this->cards($cusId)) > 1) {
                $customerSource = $this->stripe->customers->deleteSource($cusId, $cardToken);
                return $customerSource;
            }

            return "You can't delete the card";
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }

    /**
     * Set customer default card.
     *
     * @param  string  $cusId
     * @param  string  $cusSourceId
     * @return object
     */
    public function setDefaultCard($cusId, $cusSourceId)
    {
        try {
            $customer = $this->stripe->customers->update($cusId, [
                'default_source' => $cusSourceId,
            ]);

            return $customer;
        } catch (\Exception $e) {
            return (object) ['isError' => 'true', 'message' => $e->getMessage()];
        }
    }
}
