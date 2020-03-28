<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Core\Configuration;
use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * Verify documentation bugs.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DocumentationTest extends TestCase
{
    public function testConfigurationDocs()
    {
        $docs = file_get_contents(\dirname(__DIR__, 2) . '/docs/configuration.md');
        $refl = new \ReflectionClass(Configuration::class);
        $availableOptions = $refl->getConstant('AVAILABLE_OPTIONS');
        $defaultOptions = $refl->getConstant('DEFAULT_OPTIONS');

        foreach ($availableOptions as $name => $value) {
            $optionHeading = '### ' . $name;

            self::assertTrue(false !== strpos($docs, $optionHeading), sprintf('There is no mention of "%s" in the /docs/configuration.md.', $name));

            if (!isset($defaultOptions[$name])) {
                continue;
            }

            $startOfSection = $optionHeading . sprintf("\n\n**Default:** %s\n", var_export($defaultOptions[$name], true));
            if (false !== strpos($docs, $startOfSection)) {
                continue;
            }

            // Prepare a good error message.
            $start = strpos($docs, $optionHeading);
            $excerpt = substr($docs, $start, \strlen($optionHeading));
            self::assertEquals($excerpt, $startOfSection, sprintf('There is no mention of "%s"\'s default value.', $name));
        }
    }

    public function testDocumentationLinks()
    {
        $invalidLinks = [
            '(./docs/',
            '(/docs/',
            '(docs/',
        ];

        $finder = new Finder();
        $finder->in(\dirname(__DIR__, 2) . '/docs')
            ->name('*.md');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $contents = $file->getContents();
            foreach ($invalidLinks as $link) {
                self::assertTrue(false === strpos($contents, $link), sprintf('There is a broken link in "./docs/%s". Links must not start with "%s".', $file->getRelativePathname(), $link));
            }
        }
    }

    public function testClientDocs()
    {
        $readme = file_get_contents(\dirname(__DIR__, 2) . '/docs/clients/index.md');
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            self::assertTrue(false !== stripos($readme, $serviceName), sprintf('There is no mention of "%s" in the /docs/clients/index.md', $serviceName));

            if (isset($serviceData['namespace'])) {
                continue;
            }

            // See if we can find package name
            $packageName = $serviceData['package_name'];
            self::assertTrue(false !== strpos($readme, $packageName), sprintf('There is no mention of "%s" in the /docs/clients/index.md', $packageName));
            self::assertTrue(false !== strpos($readme, 'https://packagist.org/packages/' . $packageName), sprintf('There is no link "%s" on packagist', $packageName));
        }
    }

    /**
     * Verify menu follow a pattern to help the sidebar menu to select "current" item.
     */
    public function testCouscousMenu()
    {
        $config = Yaml::parse(file_get_contents(\dirname(__DIR__, 2) . '/couscous.yml'));

        foreach ($config['menu'] as $category => $categoryData) {
            $urlPrefix = isset($categoryData['section']) ? '/' . $category : '';
            foreach ($categoryData['items'] as $name => $data) {
                if ('https://' === substr($data['url'], 0, 8)) {
                    continue;
                }

                if ('index' === $name) {
                    $expected = sprintf('%s/', $urlPrefix);
                } else {
                    $expected = sprintf('%s/%s.html', $urlPrefix, $name);
                }

                self::assertEquals($expected, $data['url'], sprintf('Expected URL for "menu.%s.%s" to be "%s". Please update URL or config keys.', $category, $name, $expected));
            }
        }
    }
}
