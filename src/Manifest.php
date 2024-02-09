<?php

namespace AgeekDev\VaporIgnore;

use Laravel\VaporCli\Helpers;
use Symfony\Component\Yaml\Yaml;

class Manifest
{
    /**
     * Retrieve the manifest for the current working directory.
     */
    public static function current(): array
    {
        if (! file_exists(Path::manifest())) {
            Helpers::abort(sprintf('Unable to find a Vapor Ignore manifest at [%s].', Path::manifest()));
        }

        return Yaml::parse(file_get_contents(Path::manifest()));
    }

    /**
     * Retrieve the manifest for the current working directory.
     */
    public static function vapor(): array
    {
        if (! file_exists(Path::vaporManifest())) {
            Helpers::abort(sprintf('Unable to find a Vapor manifest at [%s].', Path::vaporManifest()));
        }

        return Yaml::parse(file_get_contents(Path::vaporManifest()));
    }

    /**
     * Get the ignored file patterns for the project.
     */
    public static function ignoredFiles(): array
    {
        return static::current()['ignore'] ?? [];
    }

    public static function isIgnoredVendor(string $name): bool
    {
        return (bool) (static::current()['vendor'][$name] ?? false);
    }

    /**
     * Write a fresh manifest file.
     */
    public static function init(): void
    {
        static::freshConfiguration();
    }

    /**
     * Write a fresh vapor-ignore manifest file.
     */
    protected static function freshConfiguration(): void
    {
        $vendor = [
            'readme' => true,
            'changelog' => true,
            'contributing' => true,
            'upgrade' => true,
            'test' => true,
            'security' => true,
            'license' => false,
            'laravel_idea' => true,
            '.github' => true,
            'dot-files' => true,
        ];

        $ignore = [
            '/tests',
        ];

        static::write([
            'vendor' => $vendor,
            'ignore' => $ignore,
        ]);
    }

    /**
     * Add clean:ignored-file command each environment to the manifest.
     */
    public static function addCleanCommandToVaporEnvironments(): void
    {
        $manifest = static::vapor();

        $command = 'php ./vendor/bin/vapor-ignore clean:ignored-file';

        $environments = $manifest['environments'] ?? [];

        foreach ($environments as $environment => $value) {
            $build = $value['build'] ?? [];
            if (! in_array($command, $build, true)) {
                $build[] = $command;
                $manifest['environments'][$environment]['build'] = $build;
            }
        }

        static::write($manifest, Path::vaporManifest());
    }

    /**
     * Write the given array to disk as the new manifest.
     */
    protected static function write(array $manifest, ?string $path = null): void
    {
        file_put_contents(
            $path ?: Path::manifest(),
            Yaml::dump($manifest, $inline = 20, $spaces = 4)
        );
    }
}
