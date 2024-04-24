<?php

if (! function_exists('getPackageName')) {
    function getPackageName(): string
    {
        return 'Vapor Ignore';
    }
}

if (! function_exists('getPackageVersion')) {
    function getPackageVersion(): string
    {
        return Composer\InstalledVersions::getRootPackage()['pretty_version'];
    }
}
