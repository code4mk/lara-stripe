## Usage

Here are some common operations you can perform using the `StripePromotion` library:

### Creating a Promotion

```php
use Code4mk\LaraStripe\Lib\StripePromotion;

$stripePromotion = new StripePromotion();

$couponId = 'coupon_id_here';

$stripePromotion->couponId($couponId) // Set the coupon ID
    ->create(); // Create the promotion
```

### Retrieving a Promotion

```php
use Code4mk\LaraStripe\Lib\StripePromotion;

$stripePromotion = new StripePromotion();

$promotionId = 'promotion_id_here';

$stripePromotion->retrieve($promotionId); // Retrieve the promotion by ID
```

### Deactivating a Promotion

```php
use Code4mk\LaraStripe\Lib\StripePromotion;

$stripePromotion = new StripePromotion();

$promotionId = 'promotion_id_here';

$stripePromotion->deactivate($promotionId); // Deactivate the promotion by ID
```

### Activating a Promotion

```php
use Code4mk\LaraStripe\Lib\StripePromotion;

$stripePromotion = new StripePromotion();

$promotionId = 'promotion_id_here';

$stripePromotion->activate($promotionId); // Activate the promotion by ID
```

### Listing Promotions

```php
use Code4mk\LaraStripe\Lib\StripePromotion;

$stripePromotion = new StripePromotion();

$stripePromotion->lists(); // Retrieve a list of all promotions
```

For more details on available methods and parameters, refer to the inline code comments and the [Stripe API documentation](https://stripe.com/docs/api/promotion_codes).

## Error Handling

The library provides basic error handling. If an error occurs during an operation, it returns an object with an `isError` property set to `true`, along with an error message and the Stripe error details.

```php
$result = $stripePromotion->create();

if ($result->isError === true) {
    // Handle the error
    echo "Error: " . $result->message;
    echo "Stripe Error: " . $result->stripe['message'];
}
```