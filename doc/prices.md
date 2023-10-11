## Usage

Here are some common operations you can perform using the `StripePrices` library:

### Creating a Subscription Price

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$stripePrices->product('product id') // Set the product id
    ->amount(10.00) // Set the price amount
    ->interval('month') // Set the billing interval (day, week, month, year)
    ->currency('usd') // Set the currency
    ->trial(7) // Set the trial period in days (optional)
    ->description('Subscription Plan') // Set a description (optional)
    ->createPrice(); // Create the subscription price
```

### Retrieving a Subscription Price

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$priceId = 'price_id_here';

$stripePrices->retrieve($priceId); // Retrieve the subscription price by ID
```

### Activating a Subscription Price

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$priceId = 'price_id_here';

$stripePrices->activate($priceId); // Activate the subscription price by ID
```

### Deactivating a Subscription Price

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$priceId = 'price_id_here';

$stripePrices->deactivate($priceId); // Deactivate the subscription price by ID
```

### Deleting a Subscription Price

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$priceId = 'price_id_here';

$stripePrices->delete($priceId); // Delete the subscription price by ID
```

### Listing Subscription Prices

```php
use Code4mk\LaraStripe\Lib\StripePrices;

$stripePrices = new StripePrices();

$stripePrices->lists(); // Retrieve a list of all subscription prices
```

For more details on available methods and parameters, refer to the inline code comments and the [Stripe API documentation](https://stripe.com/docs/api/prices).

## Error Handling

The library provides basic error handling. If an error occurs during an operation, it returns an object with an `isError` property set to `true`, along with an error message.

```php
$result = $stripePrices->createPrice();

if ($result->isError === true) {
    // Handle the error
    echo "Error: " . $result->message;
}
```