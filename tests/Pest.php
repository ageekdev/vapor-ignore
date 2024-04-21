<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

use AgeekDev\VaporIgnore\Path;

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function removeFile(string $path): void
{
    if (file_exists($path)) {
        @unlink($path);
    }
}

function removeManifest(): void
{
    removeFile(Path::defaultManifest());
}

function removeVaporManifest(): void
{
    removeFile(Path::vaporManifest());
}

function createVaporManifest()
{
    file_put_contents(Path::vaporManifest(), vaporManifestContent());
}

function vaporManifestContent(): string
{
    return <<<'YAML'
    id: 1425
    name: laravel-vapor-example
    environments:
        production:
            memory: 1024
            cli-memory: 512
            build:
                - 'composer install --no-dev --classmap-authoritative'
                - 'php artisan event:cache'
                - 'npm install && npm run prod && rm -rf node_modules'
        staging:
            memory: 1024
            cli-memory: 512
            domain: andredemos.ca
            storage: drehimself
            database: my-test-database
            build:
                - 'composer install --classmap-authoritative'
                - 'php artisan event:cache'
                - 'npm install && npm run dev && rm -rf node_modules'
            deploy:
                - 'php artisan migrate --force'
    YAML;
}

function vaporManifestContentWithCleanCommand(): string
{
    return <<<'YAML'
    id: 1425
    name: laravel-vapor-example
    environments:
        production:
            memory: 1024
            cli-memory: 512
            build:
                - 'composer install --no-dev --classmap-authoritative'
                - 'php artisan event:cache'
                - 'npm install && npm run prod && rm -rf node_modules'
                - 'php ./vendor/bin/vapor-ignore clean:ignored-files'
        staging:
            memory: 1024
            cli-memory: 512
            domain: andredemos.ca
            storage: drehimself
            database: my-test-database
            build:
                - 'composer install --classmap-authoritative'
                - 'php artisan event:cache'
                - 'npm install && npm run dev && rm -rf node_modules'
                - 'php ./vendor/bin/vapor-ignore clean:ignored-files'
            deploy:
                - 'php artisan migrate --force'

    YAML;
}
