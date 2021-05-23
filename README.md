# Basic PHP Wrapper for Deta Base

A wrapper to [Http API](https://docs.deta.sh/docs/base/http) for Deta Base, you can find more information about Deta Base [here](https://docs.deta.sh/docs/base/about).

## Installation

You can install the package via composer:

```bash
composer require vitorhugoro1/deta-base-php
```

## Usage

To make usage of this package, first do you need create a Deta Account following the tutorial [here](https://docs.deta.sh/docs/home), after create a account you will have access to `Project Key` and `Project Id` (remember to save this, because this values do not will be show again).

After have `Project Key` and `Project Id`, you can create an `Deta` instance like below:

```php
$deta = new Deta(getenv('DETA_PROJECT_ID'), getenv('DETA_PROJECT_KEY'));
```

On `Deta` class, do you have access to all this available actions:

- list items (Do not have query yet)
- create item
- get item
- update item
- delete item

## Testing

``` bash
composer test
```

## Security

If you discover any security related issues, please email vitorhugo.ro10@gmail.com instead of using the issue tracker.

## Credits

- [Vitor Merencio](https://github.com/vitorhugoro1)
- [All Contributors](../../contributors)
