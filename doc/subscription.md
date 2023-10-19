## Usage

Here are some common operations you can perform using the `StripeSubscription` library:

### Creating a Subscription

```php
use Code4mk\LaraStripe\Lib\StripeSubscription;

$stripeSubscription = new StripeSubscription();

$customerId = 'customer_id_here';
$priceId = 'price_id_here';

$stripeSubscription->customer($customerId) // Set the customer ID
    ->priceId($priceId) // Set the price ID
    ->metaData(['key' => 'value']) // Set additional metadata (optional)
    ->trial(7) // Set a custom trial period in days 
    ->quantity(1) // set the quantity
    ->source('card_source_id') // Set the card source
    ->coupon('coupon_code_here') // Apply a coupon
    ->promo('promotion_code_here') // Apply promotion
    ->create(); // Create the subscription
```

### Retrieving a Subscription

```php
use Code4mk\LaraStripe\Lib\StripeSubscription;

$stripeSubscription = new StripeSubscription();

$subscriptionId = 'subscription_id_here';

$stripeSubscription->retrieve($subscriptionId); // Retrieve the subscription by ID
```

### Canceling a Subscription

```php
use Code4mk\LaraStripe\Lib\StripeSubscription;

$stripeSubscription = new StripeSubscription();

$subscriptionId = 'subscription_id_here';

$stripeSubscription->cancel($subscriptionId); // Cancel the subscription by ID
```

For more details on available methods and parameters, refer to the inline code comments and the [Stripe API documentation](https://stripe.com/docs/api/subscriptions).

## Error Handling

The library provides basic error handling. If an error occurs during an operation, it returns an object with an `isError` property set to `true`, along with an error message.

```php
$result = $stripeSubscription->create();

if ($result->isError === true) {
    // Handle the error
    echo "Error: " . $result->message;
}
```