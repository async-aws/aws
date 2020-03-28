<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Core\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
}
