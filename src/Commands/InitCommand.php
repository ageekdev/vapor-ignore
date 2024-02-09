<?php

namespace AgeekDev\VaporIgnore\Commands;

use AgeekDev\VaporIgnore\Manifest;
use Laravel\VaporCli\Helpers;
use Laravel\VaporCli\Path;
use Symfony\Component\Console\Input\InputOption;

class InitCommand extends Command
{
    /**
     * Configure the command options.
     */
    protected function configure(): void
    {
        $this
            ->setName('init')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force override of the vapor-ignore.yml without confirmation')
            ->setDescription('Initialize vapor ignored file');
    }

    public function handle(): void
    {
        $forceOverride = $this->option('force');
        $fileExists = file_exists(Path::manifest());

        if ($fileExists && ! $forceOverride && ! Helpers::confirm('Are you sure you want to override vapor-ignore.yml', false)) {
            Helpers::abort('Action cancelled.');
        }

        Manifest::init();

        if (Helpers::confirm('Would you like to add clean command to vapor.yml now?')) {
            Manifest::addCleanCommandToVaporEnvironments();
        }

        Helpers::info('Vapor Ignore initialized successfully.');

    }
}
