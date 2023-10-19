## Usage

Here are some common operations you can perform using the `StripeCharge` library:

### Creating a charge

```php
use Code4mk\LaraStripe\Lib\StripeCharge;

$stripeCharge = new StripeCharge();

$stripeCharge->amount(10,'usd')
             //->chargeMethod('cus_OnESro2IB5rzv3', 'customer')
             ->chargeMethod('tok_1O32CXAHZl11YnN0yj3', 'card_token')
             ->metaData(['id' => '1234', 'name' => 'kamal'])
             ->create();
```

### Retrieving a Charge

```php
use Code4mk\LaraStripe\Lib\StripeCharge;

$stripeCharge = new StripeCharge();

$id = 'charge_id_here';

$stripeCharge->retrieve($id);
```

### Listing Charge

```php
use Code4mk\LaraStripe\Lib\StripeCharge;

$stripeCharge = new StripeCharge();

$stripeCharge->lists();
```

### Refund Charge

```php
use Code4mk\LaraStripe\Lib\StripeCharge;

$stripeCharge = new StripeCharge();

$stripeCharge->refund('charge_id');
```