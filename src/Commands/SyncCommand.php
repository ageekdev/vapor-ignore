<?php

namespace AgeekDev\VaporIgnore\Commands;

use AgeekDev\VaporIgnore\Manifest;
use Laravel\VaporCli\Helpers;

class SyncCommand extends Command
{
    /**
     * Configure the command options.
     */
    protected function configure(): void
    {
        $this
            ->setName('sync:ignored-files')
            ->setDescription('Sync clean:ignored-files command to vapor.yml file');
    }

    public function handle(): void
    {
        Helpers::info('Syncing clean:ignored-files command to vapor.yml file.');

        Manifest::addCleanCommandToVaporEnvironments();

        Helpers::info('Synced clean:ignored-files command to vapor.yml file.');
    }
}
