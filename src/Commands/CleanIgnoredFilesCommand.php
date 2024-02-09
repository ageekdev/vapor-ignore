<?php

namespace AgeekDev\VaporIgnore\Commands;

use AgeekDev\VaporIgnore\Manifest;
use AgeekDev\VaporIgnore\Path;
use AgeekDev\VaporIgnore\Vendor;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Laravel\VaporCli\Helpers;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class CleanIgnoredFilesCommand extends Command
{
    protected Filesystem $files;

    protected int $totalFiles = 0;

    protected int $totalDirectories = 0;

    protected int $totalFileSize = 0;

    /**
     * Configure the command options.
     */
    protected function configure(): void
    {
        $this
            ->setName('clean:ignored-files')
            ->setDescription('Clean Unnecessary Files');
    }

    public function handle(): void
    {
        if (! file_exists(Path::manifest())) {
            Helpers::abort('Unable to find vapor ignore manifest file. Please run the "php ./vendor/bin/vapor-ignore init" command first.');
        }

        $this->files = new Filesystem();

        $this->removeReadmeFiles();
        $this->removeChangeLogFiles();
        $this->removeContributingFiles();
        $this->removeUpgradeFiles();
        $this->removeTestDirectories();
        $this->removeDotFiles();
        $this->removeSecurityMdFiles();
        $this->removeLicenseFiles();
        $this->removeLaravelIdeaDirectory();
        $this->removeDotGithubDirectories();
        $this->removeOtherFiles();
        $this->removeUserIgnoredFiles();
        $this->removeVaporIgnoreYml();

        Helpers::step('<comment>Total Removed Files Size:</comment> '.round($this->totalFileSize / 1024, 2).'kb');

    }

    /**
     * Remove the readme files from the vendor.
     */
    protected function removeReadmeFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::README)) {
            $this->removeFiles([
                'readme.md',
                'README.md',
                'README.rst',
            ]);
        }
    }

    /**
     * Remove the changelog files from the vendor.
     */
    protected function removeChangeLogFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::CHANGE_LOG)) {
            Helpers::step('<options=bold>Removing CHANGELOG files</>');

            $this->removeFiles([
                'CHANGELOG.md',
                'changelog.htm',
            ]);
        }
    }

    /**
     * Remove the contributing files from the vendor.
     */
    protected function removeContributingFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::CONTRIBUTING)) {
            Helpers::step('<options=bold>Removing CONTRIBUTING files</>');

            $this->removeFiles([
                'CONTRIBUTING.md',
                'contributing.md',
            ]);
        }
    }

    /**
     * Remove the upgrade files from the vendor.
     */
    protected function removeUpgradeFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::UPGRADE)) {
            Helpers::step('<options=bold>Removing UPGRADE files</>');

            $this->removeFiles([
                'UPGRADING.md',
                'UPGRADE.md',
            ]);
        }
    }

    /**
     * Remove security md files from the vendor.
     */
    protected function removeSecurityMdFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::SECURITY)) {
            Helpers::step('<options=bold>Removing SECURITY.md files</>');

            $this->removeFiles([
                'SECURITY.md',
            ]);
        }
    }

    /**
     * Remove the test directories from the vendor.
     */
    protected function removeTestDirectories(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::TEST)) {
            Helpers::step('<options=bold>Removing Test Folders</>');

            $this->removeFiles([
                'phpunit.xml.dist',
                'phpunit.xml',
            ]);

            $finder = (new Finder())
                ->directories()->name(['test', 'tests'])
                ->in(Path::vendor().'/*/*/');

            foreach ($finder as $file) {
                Helpers::step('<comment>Removing Ignored Directory:</comment> '.str_replace(Path::current().'/', '', $file->getRealPath()));
                $this->totalFileSize += $file->getSize();
                $this->totalFiles++;
                $this->files->deleteDirectory($file->getRealPath(), true);
            }
        }
    }

    /**
     * Remove the license files from the vendor.
     */
    protected function removeLicenseFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::LICENSE)) {
            Helpers::step('<options=bold>Removing LICENSE Files</>');

            $this->removeFiles([
                'LICENSE',
                'LICENSE.txt',
                'license.txt',
                'LICENSE.md',
                'license.md',
            ]);
        }
    }

    /**
     * Remove the Laravel Idea directory from the vendor.
     */
    protected function removeLaravelIdeaDirectory(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::LARAVEL_IDEA)) {
            Helpers::step('<options=bold>Removing Laravel IDEA Folder</>');

            $this->removeDirectory(Path::vendor().'/_laravel_idea');
        }
    }

    /**
     * Remove the .github directories from the vendor.
     */
    protected function removeDotGithubDirectories(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::DOT_GITHUB)) {
            Helpers::step('<options=bold>Removing .github Folders</>');

            $finder = (new Finder())
                ->ignoreDotFiles(false)
                ->directories()->name('.github')
                ->in(Path::vendor().'/*/*/');

            if ($finder->count() === 0) {
                Helpers::step('<comment>Not Found Ignored Directory:</comment> .github/');
            }

            foreach ($finder as $file) {
                Helpers::step('<comment>Removing Ignored Directory:</comment> '.str_replace(Path::current().'/', '', $file->getRealPath()));
                $this->totalFileSize += $file->getSize();
                $this->totalDirectories++;
                $this->files->deleteDirectory($file->getRealPath(), true);
            }
        }
    }

    /**
     * Remove the other files from the vendor.
     */
    protected function removeOtherFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::OTHER_FILES)) {
            $this->removeFiles([
                'CREDITS.txt',
                'COPYRIGHT.md',
                'phpstan.neon',
            ]);
        }
    }

    /**
     * Remove the dot files from the vendor.
     */
    protected function removeDotFiles(): void
    {
        if (Manifest::isIgnoredVendor(Vendor::DOT_FILES)) {
            Helpers::step('<options=bold>Removing Dot Files</>');

            $this->removeFiles([
                '.editorconfig',
                '.gitignore',
                '.gitattributes',
                '.php_cs.dist.php',
            ], false);
        }
    }

    /**
     * Remove the given files.
     */
    protected function removeFiles($files, $ignoreDotFiles = true): void
    {
        $isEmpty = true;

        foreach ($files as $item) {
            $finder = (new Finder())
                ->files()->name($item)
                ->ignoreDotFiles($ignoreDotFiles)
                ->in(Path::vendor().'/*/*/');

            if ($finder->count() > 0) {
                $isEmpty = false;
            }

            foreach ($finder as $file) {
                $this->totalFileSize += $file->getSize();
                $this->totalFiles++;
                Helpers::step('<comment>Removing Ignored File:</comment> '.str_replace(Path::current().'/', '', $file->getRealPath()));
                $this->files->delete($file->getRealPath());
            }
        }

        if ($isEmpty) {
            Helpers::step('<comment>Not Found Ignored File:</comment> '.implode(', ', $files));
        }
    }

    /**
     * Remove the given directory.
     */
    protected function removeDirectory($directory): void
    {
        if ($this->files->isDirectory($directory)) {
            Helpers::step('<comment>Removing Ignored Directory: </comment> '.$directory.'/');
            $this->totalFileSize += $this->files->size($directory);
            $this->totalDirectories++;
            $this->files->deleteDirectory($directory, true);
        }
    }

    /**
     * Remove the user ignored files specified.
     */
    protected function removeUserIgnoredFiles(): void
    {
        $ignoredFiles = Manifest::ignoredFiles();

        if (empty($ignoredFiles)) {
            return;
        }

        Helpers::step('<options=bold>Removing User Ignored Files</>');

        $notFoundDirectories = [];

        foreach ($ignoredFiles as $pattern) {
            [$directory, $filePattern] = $this->parsePattern($pattern);

            if ($this->files->exists($directory.'/'.$filePattern) && $this->files->isDirectory($directory.'/'.$filePattern)) {
                Helpers::step('<comment>Removing Ignored Directory:</comment> '.$filePattern.'/');
                $fileSize = $this->files->size($directory.'/'.$filePattern);
                $this->files->deleteDirectory($directory.'/'.$filePattern);

                $this->totalFileSize += $fileSize;
                $this->totalDirectories++;
            } else {
                try {
                    $files = (new Finder())
                        ->in($directory)
                        ->depth('== 0')
                        ->ignoreDotFiles(false)
                        ->name($filePattern);

                    foreach ($files as $file) {
                        Helpers::step('<comment>Removing Ignored File:</comment> '.str_replace(Path::current().'/', '', $file->getRealPath()));
                        $fileSize = $this->files->size($file->getRealPath());
                        $this->files->delete($file->getRealPath());

                        $this->totalFileSize += $fileSize;
                        $this->totalFiles++;
                    }
                } catch (DirectoryNotFoundException) {
                    $notFoundDirectories[] = $directory.'/'.$filePattern;
                }
            }
        }

        foreach ($notFoundDirectories as $notFoundDirectory) {
            Helpers::step('<comment>Not Found Ignored File:</comment> '.$notFoundDirectory);
        }
    }

    /**
     * Remove the vapor ignore yml file.
     */
    protected function removeVaporIgnoreYml(): void
    {
        $this->files->delete(Path::current().'/vapor-ignore.yml');
    }

    /**
     * Parse the given ignore pattern into a base directory and file pattern.
     */
    protected function parsePattern(string $pattern): array
    {
        $filePattern = basename(trim($pattern, '/'));

        return Str::contains(trim($pattern, '/'), '/')
            ? [dirname(Path::current().'/'.trim($pattern, '/')), $filePattern]
            : [Path::current(), $filePattern];

    }
}
