<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Verify our metadata files.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MetaFileTest extends TestCase
{
    public function testComposer()
    {
        $composerFile = file_get_contents(\dirname(__DIR__, 2) . '/composer.json');
        $composer = json_decode($composerFile, true);
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            if (isset($serviceData['namespace'])) {
                continue;
            }

            self::assertTrue(\in_array(sprintf('src/Service/%s/src', $serviceName), $composer['autoload']['psr-4']), sprintf('Could not find "%s" in ./composer.json "autoload" section.', $serviceName));
            self::assertTrue(\in_array(sprintf('src/Service/%s/tests', $serviceName), $composer['autoload-dev']['psr-4']), sprintf('Could not find "%s" in ./composer.json "autoload-dev" section.', $serviceName));
        }
    }

    public function testReadme()
    {
        $readme = file_get_contents(\dirname(__DIR__, 2) . '/README.md');
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            if (isset($serviceData['namespace'])) {
                continue;
            }

            self::assertTrue(false !== strpos($readme, $serviceData['package_name']), sprintf('There is no mention of "%s" in the README.md', $serviceData['package_name']));
        }
    }

    public function testBranchAlias()
    {
        $finder = new Finder();
        $root = \dirname(__DIR__, 2);
        $finder
            ->files()
            ->in($root . '/src/*/.github/workflows')
            ->in($root . '/src/*/*/.github/workflows')
            ->in($root . '/src/*/*/*/.github/workflows')
            ->name('branch_alias.yml');

        foreach ($finder as $file) {
            $contents = $file->getContents();
            $path = $file->getRealPath();
            $relativePath = substr($path, \strlen($root) + 1);
            $packagePath = substr($relativePath, 0, -35);
            self::assertStringContainsString($packagePath, $contents, sprintf('File "%s" must include "%s"', $relativePath, $packagePath));
        }
    }
}
