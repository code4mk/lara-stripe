# Payment Checkout

LaraStripe has payment checkout (session) alias `LaraStripeCheckout`.

# Usage

```php
$checkout = LaraStripeCheckout::tnx('tnx-1212134')
                              ->amount(233)
                              ->get();
    return response()->json($checkout);

// output 
{
"session_id":"cs_test_bhbhbbh",
"public_key": "pk_test_uiuiui",
"checkout_url": ""
}
```

# stripe.js

* include `<script src="https://js.stripe.com/v3/"></script>`

~ javascript

```js

var stripe = Stripe('your_stripe_public_key');

stripe.redirectToCheckout({
  // Make the id field from the Checkout Session creation API response
  // available to this file, so you can provide it as parameter here
  // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
  sessionId: '{{CHECKOUT_SESSION_ID}}'
}).then(function (result) {
  // If `redirectToCheckout` fails due to a browser or network
  // error, display the localized error message to your customer
  // using `result.error.message`.
});
```

* blade view

~ javascript

* include `<script src="https://js.stripe.com/v3/"></script>`

```js
var publicKey = '{{ $session->public_key }}'
var SessionId = '{{ $session->session_id }}'
var stripe = Stripe(publicKey);

stripe.redirectToCheckout({
  sessionId: SessionId
}).then(function (result) {

});
```

* sessionId will be that code which generate  `getSession method`

# retrive data `success route`

After success payment stripe redirect to success_url . retrive session data in success_url.

```php
Route::get('success',function(Request $request){
    $data = LaraStripeCheckout::setup([
        'secret' => '******',
        'public_key' => '******',
        'currency' => 'usd'
    ])
    ->retrieve($request->session_id);
    $data->ref_id;
    // return `customer ID` `cart ID`, or `similar`
})
```

# refund

* Store payment_intent in db when checkout success. ('retrieve method')  

```php
$re = LaraStripeCheckout::setup(['secret' => '******'])
                    ->refund('payment_intent')
return response()->json($re);
```

# reference

* [session stripe](https://stripe.com/docs/api/checkout/sessions/object#checkout_session_object-id)
* [stripe official doc](https://stripe.com/payments/checkout)
* [stripe payment](https://stripe.com/docs/terminal/payments)
