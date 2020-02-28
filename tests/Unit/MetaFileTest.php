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
    public function testPHPUnit()
    {
        $phpunitFile = file_get_contents(\dirname(__DIR__, 2) . '/phpunit.xml.dist');
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            if (isset($serviceData['namespace'])) {
                continue;
            }
            self::assertStringContainsStringIgnoringCase(sprintf('src/%s/tests', $serviceName), $phpunitFile, sprintf('Could not find "%s" in ./phpunit.xml.dist', $serviceName));
        }
    }

    public function testComposer()
    {
        $composerFile = file_get_contents(\dirname(__DIR__, 2) . '/composer.json');
        $composer = json_decode($composerFile, true);
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            if (isset($serviceData['namespace'])) {
                continue;
            }

            self::assertTrue(\in_array(sprintf('src/%s/src', $serviceName), $composer['autoload']['psr-4']), sprintf('Could not find "%s" in ./composer.json "autoload" section.', $serviceName));
            self::assertTrue(\in_array(sprintf('src/%s/tests', $serviceName), $composer['autoload-dev']['psr-4']), sprintf('Could not find "%s" in ./composer.json "autoload-dev" section.', $serviceName));
        }
    }
}
