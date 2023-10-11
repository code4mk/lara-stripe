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

  /**
   * Set the product name.
   * 
   * @param string $name product name.
   * @return $this
   */
  public function name($name)
  {
    $this->productName = $name;
    return $this;
  }

  /**
   * Create a new product.
   * 
   * @return object
   */
  public function create()
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

  /**
   * Retrieve a product with $id
   * 
   * @param string|integer $id
   * @return object
   */
  public function retrieve($id)
  {
    try {
      $product = $this->stripe->products->retrieve($id);
      return $product;
    } catch (\Exception $e) {
        return (object) ['isError' => 'true', 'message' => $e->getMessage()];
    }
  }

  /**
   * Delete a product with $id
   * 
   * @param string|integer $id
   * @return object
   */
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