Sure, here's a step-by-step guide on setting up a subscription in Stripe using the `StripeProducts` and `StripeSubscription` classes:

**1. Create a Product**

Use the `StripeProducts` class to create a product:

```php
use Code4mk\LaraStripe\Lib\StripeProducts;

$stripeProducts = new StripeProducts();

$productName = 'Your Product Name';

$product = $stripeProducts->name($productName)
    ->create();
```

**2. Set Price (Plan) with Product ID**

Now that you have a product, you can set a price for that product. Use the product's ID when creating the price:

```php
use Code4mk\LaraStripe\Lib\StripePrices; // Assuming you have a StripePrices class
$pricesInstance = new StripePrices();
$price = $stripePrices->description('gqa plan 1 - 10usd')
                ->amount(10)
                ->currency('usd')
                ->product($product->id)
                ->interval('day')
                ->createPrice();
```

**3. Create a Customer**

Use the `StripeCustomer` class to create a customer. Make sure to collect the necessary customer information:

```php
use Code4mk\LaraStripe\Lib\StripeCustomer; // Assuming you have a StripeCustomer class

$stripeCustomer = new StripeCustomer();

$customer = $stripeCustomer->name('Customer Name')
                           ->email('customer@gmail.com')
                           ->create()
```

**4. Add a Subscription**

Finally, add a subscription for the customer using the `StripeSubscription` class:

```php
use Code4mk\LaraStripe\Lib\StripeSubscription;

$stripeSubscription = new StripeSubscription();

$customerId = $customer->id; // Use the customer's ID
$priceId = $price->id; // Use the price's ID

$subscription = $stripeSubscription->customer($customerId)
                                   ->priceId($priceId)
                                   ->create();
```

Now you have set up a subscription for a customer, starting with the creation of a product, setting a price (plan) for that product, creating a customer, and finally adding a subscription to the customer.