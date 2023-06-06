# Payment Checkout

LaraStripe has payment checkout (session) alias `LaraStripeCheckout`.

# Usage

## env 

```
STRIPE_PUBLIC_KEY="public key"
STRIPE_SECRET_KEY="secret key"
STRIPE_SUCCESS_URL="http://127.0.0.1:8000/checkout/success"
STRIPE_CANCEL_URL="http://127.0.0.1:8000/checkout/cancel"

```

## checkout

```php
$checkout =  LaraStripeCheckout::tnx('tnx-1212134')
                            ->amount('236')
                            ->additionalData(['transaction_id' => 'tnx-1212'])
                            ->get();
return response()->json($checkout);

// output 
{
"session_id":"cs_test_bhbhbbh",
"public_key": "pk_test_uiuiui",
"checkout_url": ""
}
```

### stripe.js

* include `<script src="https://js.stripe.com/v3/"></script>`

~ javascript

```js

var stripe = Stripe('your_stripe_public_key');

stripe.redirectToCheckout({
  sessionId: '{{CHECKOUT_SESSION_ID}}'
}).then(function (result) {
  // If `redirectToCheckout` fails due to a browser or network
  // error, display the localized error message to your customer
  // using `result.error.message`.
});
```

### blade view

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


## success

After success payment stripe redirect to success_url . retrive session data in success_url.

```php
Route::get('checkout/success',function(Request $request){
    $data = LaraStripeCheckout::status(request('session_id'));
    return response()->json($data);

    // status (succeeded, .....)
    $data->status;
    
    // tnx id
    $data->ref_id;

    // additional data (metada)
    $data->sessions->metadata;

    // payment status
    $data->sessions->payment_status;
});
```

## refund

* Store payment_intent in db when checkout success. ('retrieve method')  

```php
Route::get('checkout/refund',function(){
  $charge = LaraStripeCheckout::refund('payment_intent');
    return response()->json($charge);
});
```
