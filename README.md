## Packages

[SEOTools](https://github.com/artesaos/seotools)
[Laravel FFMpeg](https://packagist.org/packages/pbmedia/laravel-ffmpeg)
[Laravel cookie consent](https://github.com/spatie/laravel-cookie-consent)


## Stripe Dev Mode

Listen stripe's events

```
docker run --network="lms_backend" --rm -it stripe/stripe-cli listen    --forward-to http://host.docker.internal:8080/api/v1/stripe/webhook --skip-verify --api-key STRIPE_KEY
```

Send stripe's event
```
docker run --rm -it stripe/stripe-cli trigger payment_intent.created --api-key STRIPE_KEY
```