<?php

namespace AgeekDev\VaporIgnore;

use Laravel\VaporCli\Helpers;

class Path
{
    /**
     * Get the path to the vendor directory.
     */
    public static function vendor(): string
    {
        return static::current().'/vendor';
    }

    /**
     * Get the path to the current working directory.
     */
    public static function current(): string
    {
        return getcwd();
    }

    /**
     * Get the path to the Vapor build directory.
     */
    public static function build(): string
    {
        return static::vapor().'/build';
    }

    /**
     * Get the path to the project's manifest file.
     */
    public static function manifest(): string
    {
        return Helpers::app('manifest');
    }

    /**
     * Get the path to the default manifest file location.
     */
    public static function defaultManifest(): string
    {
        return getcwd().'/vapor-ignore.yml';
    }

    /**
     * Get the path to the default manifest file location.
     */
    public static function vaporManifest(): string
    {
        return getcwd().'/vapor.yml';
    }

    /**
     * Get the path to the hidden Vapor directory.
     */
    public static function vapor(): string
    {
        return getcwd().'/.vapor';
    }
}
