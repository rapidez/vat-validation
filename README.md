# Rapidez vat-validation

Implements VIES validation to use on VAT fields in Rapidez

## Installation

```
yarn add jsvat --dev
composer require rapidez/vat-validation
```

## Usage

You can add the VAT check to an input by adding this to the input:
```
v-on:change="window.app.$emit('vat-change', $event)"
```

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
