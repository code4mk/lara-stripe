 ## Usage

Here's how you can use the Stripe Customer Library to perform various customer-related actions in your Laravel application.

### Initialization

```php
use Code4mk\LaraStripe\Lib\StripeCustomer;

// Initialize the Stripe Customer instance
$stripeCustomer = new StripeCustomer();
```

### Creating a Customer

You can create a new customer by specifying their name, email, and optional metadata. 

```php
$customer = $stripeCustomer
    ->name($customerName)
    ->email($customerEmail)
    ->metadata($metadata)
    ->create();

if ($customer->isError) {
    // Handle errors
    echo $customer->message;
} else {
    // Customer created successfully
}
```

### Retrieving a Customer

You can retrieve a customer by their ID.

```php
$customer = $stripeCustomer->retrieve($customerId);

if ($customer->isError) {
    // Handle errors
    echo $customer->message;
} else {
    // Use $customer to access customer data
}
```

### Deleting a Customer

You can delete a customer by their ID.

```php
$customer = $stripeCustomer->delete($customerId);

if ($customer->isError) {
    // Handle errors
    echo $customer->message;
} else {
    // Customer deleted successfully
}
```

### Retrieving All Customers

You can retrieve a list of all customers.

```php
$customers = $stripeCustomer->lists();

if ($customers->isError) {
    // Handle errors
    echo $customers->message;
} else {
    // Use $customers to access the list of customers
}
```

### Managing Customer Cards

You can manage a customer's cards, such as listing, adding, deleting, or setting a default card.

#### Listing Customer Cards

```php
$cards = $stripeCustomer->cards($customerId);

if ($cards->isError) {
    // Handle errors
    echo $cards->message;
} else {
    // Use $cards to access the list of customer cards
}
```

#### Adding a New Card

```php
$cardToken = 'your_stripe_card_token';

$response = $stripeCustomer->addCard($customerId, $cardToken);

if ($response->isError) {
    // Handle errors
    echo $response->message;
} else {
    // Card added successfully
}
```

#### Deleting a Card

```php
$cardId = 'stripe_card_id';

$response = $stripeCustomer->deleteCard($customerId, $cardId);

if ($response->isError) {
    // Handle errors
    echo $response->message;
} else {
    // Card deleted successfully
}
```

#### Setting Default Card

```php
$defaultCardId = 'stripe_default_card_id';

$response = $stripeCustomer->setDefaultCard($customerId, $defaultCardId);

if ($response->isError) {
    // Handle errors
    echo $response->message;
} else {
    // Default card set successfully
}
```

