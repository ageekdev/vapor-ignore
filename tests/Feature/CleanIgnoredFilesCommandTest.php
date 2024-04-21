<?php

use AgeekDev\VaporIgnore\Commands\CleanIgnoredFilesCommand;
use AgeekDev\VaporIgnore\Manifest;
use AgeekDev\VaporIgnore\Path;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertStringContainsString;

it('can run clean:ignored-files command', function (): void {
    Manifest::init([
        'vendor' => [],
    ]);

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(CleanIgnoredFilesCommand::class);

    $tester = new CommandTester($command);

    $tester->execute([
        'command' => $command->getName(),
    ]);

    $tester->assertCommandIsSuccessful();

    assertFileDoesNotExist(Path::defaultManifest());

    assertStringContainsString('Total Removed Files Size:', $tester->getDisplay());
});
