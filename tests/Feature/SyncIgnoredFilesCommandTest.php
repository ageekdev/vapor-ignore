<?php

use AgeekDev\VaporIgnore\Commands\SyncCommand;
use AgeekDev\VaporIgnore\Path;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringMatchesFormatFile;

it('can run sync:ignored-files command', function (): void {

    createVaporManifest();

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(SyncCommand::class);

    $tester = new CommandTester($command);

    $tester->execute([
        'command' => $command->getName(),
    ]);

    $tester->assertCommandIsSuccessful();

    assertFileExists($path = Path::vaporManifest());

    assertStringMatchesFormatFile($path, vaporManifestContentWithCleanCommand());

    assertStringContainsString('Syncing clean:ignored-files command to vapor.yml file.', $tester->getDisplay());
    assertStringContainsString('Synced clean:ignored-files command to vapor.yml file.', $tester->getDisplay());
});
