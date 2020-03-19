<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;

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
        $readme = file_get_contents(\dirname(__DIR__, 2) . '/Readme.md');
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            if (isset($serviceData['namespace'])) {
                continue;
            }

            self::assertTrue(false !== strpos($readme, $serviceData['package_name']), sprintf('There is no mention of "%s" in the Readme.md', $serviceData['package_name']));
        }
    }
}
