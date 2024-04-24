<?php

use AgeekDev\VaporIgnore\Commands\InitCommand;
use AgeekDev\VaporIgnore\Manifest;
use AgeekDev\VaporIgnore\Path;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertStringMatchesFormatFile;

it('can run init command', function (): void {
    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(InitCommand::class);

    $tester = new CommandTester($command);

    $tester->setInputs([
        'no',
    ]);

    $tester->execute([
        'command' => $command->getName(),
    ]);

    assertInitCommandRunPorperly($tester, Path::defaultManifest(), Yaml::dump(Manifest::freshConfiguration()));
});

it('can run init command with force option', function (): void {
    touch(Path::defaultManifest());

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(InitCommand::class);

    $tester = new CommandTester($command);

    $tester->setInputs([
        'no',
    ]);

    $tester->execute([
        'command' => $command->getName(),
        '--force' => true,
    ]);

    assertInitCommandRunPorperly($tester, Path::defaultManifest(), Yaml::dump(Manifest::freshConfiguration()));
});

it('can run init command and override', function (): void {
    touch(Path::defaultManifest());

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(InitCommand::class);

    $tester = new CommandTester($command);

    $tester->setInputs([
        'yes',
        'no',
    ]);

    $tester->execute([
        'command' => $command->getName(),
    ]);

    assertInitCommandRunPorperly($tester, Path::defaultManifest(), Yaml::dump(Manifest::freshConfiguration()));
});

it('can run init command with custom manifest', function (): void {
    $customManifest = getcwd().'/vapor-ignore-test.yml';

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(InitCommand::class);

    $tester = new CommandTester($command);

    $tester->setInputs([
        'no',
    ]);

    $tester->execute([
        'command' => $command->getName(),
        '--manifest' => $customManifest,
    ]);

    assertInitCommandRunPorperly($tester, $customManifest, Yaml::dump(Manifest::freshConfiguration()));

    removeFile($customManifest);
});

it('can run init command and add clean command', function (): void {
    createVaporManifest();

    /**
     * @var TestCase $this
     */
    $command = $this->resolveCommand(InitCommand::class);

    $tester = new CommandTester($command);

    $tester->setInputs([
        'yes',
    ]);

    $tester->execute([
        'command' => $command->getName(),
    ]);

    assertInitCommandRunPorperly($tester, Path::defaultManifest(), Yaml::dump(Manifest::freshConfiguration()));

    assertFileExists($vaporPath = Path::vaporManifest());

    assertStringMatchesFormatFile($vaporPath, vaporManifestContentWithCleanCommand());
});

function assertInitCommandRunPorperly(CommandTester $tester, string $path, string $format): void
{
    $tester->assertCommandIsSuccessful();

    assertFileExists($path);

    assertStringMatchesFormatFile($path, $format);
}
