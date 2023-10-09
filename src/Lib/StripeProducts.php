<?php

namespace Code4mk\LaraStripe\Lib;

use Stripe\StripeClient;

class StripeProducts
{
  private $secretKey;
  private $stripe;

  private $productName;

  public function __construct()
  {
      $this->secretKey = config('lara-stripe.secret_key');
      $this->stripe = new StripeClient($this->secretKey);
  }

  public function name($name)
  {
    $this->productName = $name;
    return $this;
  }

  public function  create()
  {
    $productData = [
      'name' => $this->productName
    ];

    try {
      $product = $this->stripe->products->create($productData);
      return $product;
    } catch (\Exception $e) {
        return (object) ['isError' => 'true', 'message' => $e->getMessage()];
    }
  }

  public function retrieve($id)
  {
    try {
      $product = $this->stripe->products->retrieve($id);
      return $product;
    } catch (\Exception $e) {
        return (object) ['isError' => 'true', 'message' => $e->getMessage()];
    }
  }

  public function delete($id)
  {
    try {
      $product = $this->stripe->products->delete($id);
      return $product;
    } catch (\Exception $e) {
        return (object) ['isError' => 'true', 'message' => $e->getMessage()];
    }
  }

}