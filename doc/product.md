## Usage

Here are some common operations you can perform using the `StripeProducts` library:

### Creating a Product

```php
use Code4mk\LaraStripe\Lib\StripeProducts;

$stripeProducts = new StripeProducts();

$productName = 'Product Name';

$stripeProducts->name($productName) // Set the product name
    ->create(); // Create the product
```

### Retrieving a Product

```php
use Code4mk\LaraStripe\Lib\StripeProducts;

$stripeProducts = new StripeProducts();

$productId = 'product_id_here';

$stripeProducts->retrieve($productId); // Retrieve the product by ID
```

### Deleting a Product

```php
use Code4mk\LaraStripe\Lib\StripeProducts;

$stripeProducts = new StripeProducts();

$productId = 'product_id_here';

$stripeProducts->delete($productId); // Delete the product by ID
```

For more details on available methods and parameters, refer to the inline code comments and the [Stripe API documentation](https://stripe.com/docs/api/products).

## Error Handling

The library provides basic error handling. If an error occurs during an operation, it returns an object with an `isError` property set to `true`, along with an error message.

```php
$result = $stripeProducts->create();

if ($result->isError === true) {
    // Handle the error
    echo "Error: " . $result->message;
}
```