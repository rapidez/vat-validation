# Rapidez vat-validation

Implements VIES validation to use on VAT fields in Rapidez.

This package makes use of [jsvat](https://github.com/se-panfilov/jsvat) to pre-validate the formatting of the given VAT number before sending it to the official [VIES API](https://ec.europa.eu/taxation_customs/vies/).

## Installation

```
yarn add jsvat --dev
composer require rapidez/vat-validation
```

## Usage

Every Rapidez package will work with this package out of the box, and will not require any configuration.

However, if you're using your own fields, you can add the VAT check to an input by adding this to the input:
```
v-on:change="window.app.$emit('vat-change', $event)"
```

## VIES validation

To validate VAT numbers we use [this package](https://github.com/ibericode/vat) which handles the connection to the VIES API.

Because the VIES API is notably unreliable and can't handle that many requests, the script throttles requests to a maximum of one request every 5 seconds in the frontend.
As this is an API route, it will also be limited by the standard Laravel API rate limit.

Finally, this package also caches the result of VAT validation requests for 1 week by default. This cache time can be changed in the config file (see below).

## Configuration

You can publish the config with:
```
php artisan vendor:publish --tag=rapidez-vat-config
```

## Translations

You can publish the translation file with:
```
php artisan vendor:publish --tag=rapidez-vat-translations
```

## License

GNU General Public License v3. Please see [License File](LICENSE) for more information.
