<h1 align="center">Vapor Ignore</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ageekdev/vapor-ignore.svg?style=flat-square)](https://packagist.org/packages/ageekdev/vapor-ignore)
[![Laravel 9.x](https://img.shields.io/badge/Laravel-9.x-red.svg?style=flat-square)](https://laravel.com/docs/9.x)
[![Laravel 10.x](https://img.shields.io/badge/Laravel-10.x-red.svg?style=flat-square)](http://laravel.com/docs/10.x)
[![Laravel 11.x](https://img.shields.io/badge/Laravel-11.x-red.svg?style=flat-square)](http://laravel.com/docs/11.x)
[![Total Downloads](https://img.shields.io/packagist/dt/ageekdev/vapor-ignore.svg?style=flat-square)](https://packagist.org/packages/ageekdev/vapor-ignore)

Vapor Ignore is a package that helps you to clean unnecessary files before deploying your Laravel application to [Laravel Vapor](https://vapor.laravel.com/).

## Installation

You can install the package via composer:

```bash
composer require ageekdev/vapor-ignore
```

## Getting Started

After installing the package, you can run the following command to initialize. This command will create a `vapor-ignore.yml` file in the root of your project.

```bash
php ./vendor/bin/vapor-ignore init
```

### vapor-ignore.yml

#### `ignore` directive

Ignore the specified file or directory.

#### `vendor` directive

Ignore the specified file or directory from the `vendor` directory.

-   readme - Ignore the `readme.md` file from the `vendor` directory.
-   changelog - Ignore the `changelog.md` file from the `vendor` directory.
-   contributing - Ignore the `contributing.md` file from the `vendor` directory.
-   upgrade - Ignore the `upgrade.md` file from the `vendor` directory.
-   tests - Ignore the `tests` directory from the `vendor` directory.
-   security - Ignore the `security.md` file from the `vendor` directory.
-   license - Ignore the `license.md` file from the `vendor` directory.
-   laravel-idea - Ignore the `laravel-idea` directory from the `vendor` directory.
-   .github - Ignore the `.github` directory from the `vendor` directory.
-   dotfiles - Ignore the dotfiles(`.editorconfig`, `.gitignore`, `.gitattributes` and `.php_cs.dist.php`) from the `vendor` directory.

## Testing
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Tint Naing Win](https://github.com/tintnaingwinn)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
