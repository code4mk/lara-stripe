<p align="center" ><img src="https://user-images.githubusercontent.com/17185462/67279915-d2a58480-f4ed-11e9-868b-3e467b8552a2.png"></p>

# LaraStripe
Laravel Stripe payment

# Installation

```bash
composer require code4mk/lara-stripe
```

# Setup

## vendor publish

```bash
php artisan vendor:publish --provider="Code4mk\LaraStripe\LaraStripeServiceProvider" --tag=config
```

## Set .env 

```
STRIPE_PUBLIC_KEY=""
STRIPE_SECRET_KEY=""
STRIPE_SUCCESS_URL=""
STRIPE_CANCEL_URL=""
STRIPE_IS_PRODUCTION="true|false"
```

# Documentation

* [doc](https://github.com/code4mk/lara-stripe/tree/master/doc)
* [Charge doc](https://github.com/code4mk/lara-stripe/blob/master/doc/charge.md)
* [Checkout doc](https://github.com/code4mk/lara-stripe/blob/master/doc/payment-checkout.md)
* [Customer doc](https://github.com/code4mk/lara-stripe/blob/master/doc/customer.md)
* [Product doc](https://github.com/code4mk/lara-stripe/blob/master/doc/product.md)
* [Prices doc](https://github.com/code4mk/lara-stripe/blob/master/doc/prices.md)
* [Subscription doc](https://github.com/code4mk/lara-stripe/blob/master/doc/subscription.md)
* [Coupon doc](https://github.com/code4mk/lara-stripe/blob/master/doc/coupon.md)
* [promotion doc](https://github.com/code4mk/lara-stripe/blob/master/doc/promotion.md)

# Demo repo

* [lara-stripe-demo github](https://github.com/code4mk/lara-stripe-demo)

# Courtesy

* [stripe/stripe-php](https://github.com/stripe/stripe-php)
* https://jsfiddle.net/ywain/o2n3js2r/
