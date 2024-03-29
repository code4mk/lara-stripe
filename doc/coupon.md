## Usage

Here are some common operations you can perform using the `StripeCoupon` library:

### Creating a Coupon

```php
use Code4mk\LaraStripe\Lib\StripeCoupon;

$stripeCoupon = new StripeCoupon();

$stripeCoupon->name('my_coupon') // Set the coupon name
    ->amount(10.00) // Set the coupon amount
    ->duration('once') // Set the coupon duration
    ->create(); // Create the coupon
```

### Retrieving a Coupon

```php
use Code4mk\LaraStripe\Lib\StripeCoupon;

$stripeCoupon = new StripeCoupon();

$couponId = 'coupon_id_here';

$stripeCoupon->retrieve($couponId); // Retrieve the coupon by ID
```

### Listing Coupons

```php
use Code4mk\LaraStripe\Lib\StripeCoupon;

$stripeCoupon = new StripeCoupon();

$stripeCoupon->lists(); // Retrieve a list of all coupons
```

### Deleting a Coupon

```php
use Code4mk\LaraStripe\Lib\StripeCoupon;

$stripeCoupon = new StripeCoupon();

$couponId = 'coupon_id_here';

$stripeCoupon->delete($couponId); // Delete the coupon by ID
```

For more details on available methods and parameters, refer to the inline code comments and the [Stripe API documentation](https://stripe.com/docs/api/coupons).

## Error Handling

The library provides basic error handling. If an error occurs during an operation, it returns an object with an `isError` property set to `true`, along with an error message and the Stripe error details.

```php
$result = $stripeCoupon->create();

if ($result->isError === true) {
    // Handle the error
    echo "Error: " . $result->message;
    echo "Stripe Error: " . $result->stripe['message'];
}
```