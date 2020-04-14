# Translate Directive

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Directive <translate> to translate content using different provider APIs

## Install

Via Composer

``` bash
composer require getpop/translate-directive dev-master
```

**Note:** Your `composer.json` file must have the configuration below to accept minimum stability `"dev"` (there are no releases for PoP yet, and the code is installed directly from the `master` branch):

```javascript
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```

## Usage

Extend from class `AbstractTranslateDirectiveResolver` to implement the translation directive using a specific API provider.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Static Analysis

Execute [phpstan](https://github.com/phpstan/phpstan) with level 8 (strictest mode):

``` bash
composer analyse
```

To run checks for level 0 (or any level from 0 to 8), use:

``` bash
./vendor/bin/phpstan analyse -l 0 src tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/translate-directive.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/translate-directive/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/translate-directive.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/translate-directive.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/translate-directive.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/translate-directive
[link-travis]: https://travis-ci.org/getpop/translate-directive
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/translate-directive/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/translate-directive
[link-downloads]: https://packagist.org/packages/getpop/translate-directive
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors

