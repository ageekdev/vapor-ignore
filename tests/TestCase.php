<?php

namespace Tests;

use AgeekDev\VaporIgnore\Application;
use AgeekDev\VaporIgnore\Commands\Command;
use AgeekDev\VaporIgnore\Path;
use Illuminate\Container\Container;
use Laravel\VaporCli\Helpers;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

abstract class TestCase extends BaseTestCase
{
    protected SymfonyConsoleApplication $application;

    /**
     * @param  class-string<Command>  $command
     */
    protected function resolveCommand(string $commandName): Command
    {
        $command = new $commandName;

        $command->setApplication($this->application);

        return $command;
    }

    protected function setUp(): void
    {
        $this->removeManifest();

        Container::setInstance(new Container);

        $app = new Application(getPackageName(), getPackageVersion());

        $app->setAutoExit(false);

        $this->application = $app;

        Helpers::app()->offsetSet('manifest', Path::defaultManifest());
    }

    protected function tearDown(): void
    {
        $this->removeManifest();
    }

    protected function removeManifest(): void
    {
        removeManifest();
        removeVaporManifest();
    }
}
