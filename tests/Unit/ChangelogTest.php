<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Verify CHANGELOG entries are present.
 */
class ChangelogTest extends TestCase
{
    #[DataProvider('provideChangedlogFiles')]
    public function testChangelogFormat(string $changelogPath)
    {
        $lines = explode("\n", file_get_contents($changelogPath));

        self::assertGreaterThan(3, \count($lines), 'CHANGELOG must not be empty');
        self::assertSame('# Change Log', $lines[0], 'CHANGELOG MUST start with "# Change Log"');
        self::assertSame('', $lines[1], 'CHANGELOG MUST have a second line empty');
        self::assertSame('## NOT RELEASED', $lines[2], 'CHANGELOG MUST have a "## NOT RELEASED" section');

        $inTitle1 = false;

        $inTitle2 = false;
        $title2Empty = false;

        $inTitle3 = false;
        $title3Empty = false;

        $title3Choices = [
            '### BC-BREAK', '### Removed', // Major
            '### Added', '### Deprecated', '### Dependency bumped', // Minor
            '### Changed', '### Fixed', '### Security', // Patch
        ];
        $lastTitle3 = -1;
        $needEmptyLine = false;
        $wasEmptyLine = false;
        $finalWords = false;
        foreach ($lines as $index => $line) {
            if ($needEmptyLine) {
                self::assertSame('', $line, 'CHANGELOG line ' . $index . ' MUST be empty');
                $needEmptyLine = false;
                $wasEmptyLine = true;

                continue;
            }
            self::assertFalse($finalWords, 'CHANGELOG line ' . $index . ' MUST not contains anything after "First version"');
            if ('' === $line) {
                $wasEmptyLine = true;

                continue;
            }

            if (0 === strpos($line, '# ')) {
                self::assertFalse($inTitle1, 'CHANGELOG line ' . $index . ' MUST contain a single Title');
                $inTitle1 = true;
                $needEmptyLine = true;
                $wasEmptyLine = false;

                continue;
            }

            if (0 === strpos($line, '## ')) {
                self::assertTrue($wasEmptyLine, 'CHANGELOG line ' . ($index - 1) . ' MUST be empty');
                self::assertTrue($inTitle1, 'CHANGELOG line ' . $index . ' MUST have title2 inside a title1');
                self::assertFalse($title2Empty, 'CHANGELOG line ' . $index . ' MUST not have empty title2');
                self::assertFalse($title3Empty, 'CHANGELOG line ' . $index . ' MUST not have empty title3');
                self::assertMatchesRegularExpression('/^## (NOT RELEASED|\d+\.\d+\.\d+)$/', $line, 'CHANGELOG line ' . $index . ' MUST be "## X.Y.Z"');
                $inTitle2 = true;
                $inTitle3 = false;
                $lastTitle3 = -1;
                $title2Empty = '## NOT RELEASED' !== $line; // allows having empty section for `## NOT RELEASED`
                $title3Empty = false;
                $wasEmptyLine = false;

                $needEmptyLine = true;

                continue;
            }

            if (0 === strpos($line, '### ')) {
                self::assertTrue($wasEmptyLine, 'CHANGELOG line ' . ($index - 1) . ' MUST be empty');
                self::assertTrue($inTitle2, 'CHANGELOG line ' . $index . ' MUST have title2 inside a title2');
                self::assertFalse($title3Empty, 'CHANGELOG line ' . $index . ' MUST not have empty title3');
                self::assertContains($line, $title3Choices, 'CHANGELOG line ' . $index . ' MUST be "' . implode('" or "', $title3Choices) . '"');
                $newTitle3Index = array_search($line, $title3Choices, true);
                self::assertGreaterThan($lastTitle3, $newTitle3Index, 'CHANGELOG line ' . $index . ' MUST respect the order "' . implode('" then "', $title3Choices) . '"');

                $lastTitle3 = $newTitle3Index;
                $title2Empty = false;
                $inTitle3 = true;
                $title3Empty = true;
                $wasEmptyLine = false;

                $needEmptyLine = true;

                continue;
            }

            $wasEmptyLine = false;
            if ($inTitle3) {
                self::assertMatchesRegularExpression('/^[ -] /', $line, 'CHANGELOG entry at line ' . $index . ' MUST start with "- " or "  " for multiline content');
                $title3Empty = false;

                continue;
            }

            if ('- Empty release' === $line) {
                $needEmptyLine = true;
                $inTitle3 = false;
                $title2Empty = false;

                continue;
            }

            if ('First version' === $line) {
                $finalWords = true;
                $needEmptyLine = true;

                continue;
            }

            self::assertFalse('CHANGELOG entry at line ' . $index . ' is not expected');
        }

        self::assertTrue($finalWords, 'CHANGELOG MUST contains "First version"');
    }

    #[DataProvider('provideChangedServicesWithoutChangelog')]
    public function testChangelogEntryForService(string $service, string $base, bool $isCommentOnly)
    {
        if ('' === $service) {
            self::markTestSkipped('Nothing to test');
        }

        if (!$isCommentOnly) {
            self::fail('Missing CHANGELOG entry for package ' . $service);
        }

        $changelog = explode("\n", file_get_contents($base . '/CHANGELOG.md'));
        $nrSection = false;

        foreach ($changelog as $line) {
            if ('## NOT RELEASED' === $line) {
                $nrSection = true;

                continue;
            }
            if (!$nrSection) {
                continue;
            }
            if (0 === strpos($line, '## ')) {
                break;
            }

            if ('- AWS enhancement: Documentation updates.' === $line) {
                $this->expectNotToPerformAssertions();

                return;
            }
        }

        self::fail('Missing CHANGELOG entry for package ' . $service);
    }

    public static function provideChangedlogFiles(): iterable
    {
        $finder = (new Finder())
            ->in(\dirname(__DIR__, 2) . '/src')
            ->name('CHANGELOG.md');

        foreach ($finder as $file) {
            yield $file->getRelativePath() => [$file->getRealPath()];
        }
    }

    public static function provideChangedServicesWithoutChangelog(): iterable
    {
        $branches = array_filter(
            array_map(static function ($line) {
                return trim(substr($line, 1));
            }, self::call('git branch -a --color=never')),
            static function ($branch) {
                return 'master' === $branch || '/master' === substr($branch, -7);
            }
        );

        if (0 === \count($branches)) {
            yield [null, null, null];
        }

        usort($branches, static function ($a, $b) {
            return \strlen($a) <=> \strlen($b);
        });

        $output = array_merge(
            self::call('git diff --numstat HEAD -- src/'),
            self::call('git diff --numstat ' . $branches[0] . '...HEAD -- src/')
        );

        $changedServices = [];
        foreach ($output as $line) {
            $file = explode("\t", $line)[2];
            $parts = explode('/', $file);
            if ('src' !== $parts[0]) {
                continue;
            }
            if (!isset($parts[1])) {
                continue;
            }

            if ('Service' === $parts[1]) {
                $service = $parts[2];
                $base = 'src/Service/' . $service;

                if ('.template' === $service) {
                    continue; // The service template does not have an actual changelog
                }
            } elseif ('Integration' === $parts[1]) {
                $service = $parts[2] . '/' . $parts[3];
                $base = 'src/Integration/' . $service;
            } elseif ('CodeGenerator' === $parts[1]) {
                continue; // The code generator does not have a changelog as it has no releases
            } elseif ('Core' === $parts[1]) {
                $service = $parts[1];
                $base = 'src/' . $service;
            } else {
                continue;
            }

            $subPath = substr($file, \strlen($base));
            $normalizedSubPath = str_replace(\DIRECTORY_SEPARATOR, '/', $subPath);

            if (\in_array($normalizedSubPath, ['/README.md', '/Makefile', '/.gitattributes', '/.gitignore', '/LICENSE', '/phpunit.xml.dist', '/roave-bc-check.yaml'], true) || str_starts_with($normalizedSubPath, '/.github/') || str_starts_with($normalizedSubPath, '/tests/')) {
                // Scaffolding files don't require a changelog entry when modified as they don't impact the usage of the packages
                continue;
            }

            if (!isset($changedServices[$service])) {
                $changedServices[$service] = ['base' => $base, 'files' => []];
            }
            $changedServices[$service]['files'][] = $subPath;
        }

        $oneChange = false;
        foreach ($changedServices as $service => $changesService) {
            $changedFiles = $changesService['files'];
            if (\in_array('/CHANGELOG.md', $changedFiles, true)) {
                continue;
            }

            $isCommentOnly = true;
            foreach ($changedFiles as $changedFile) {
                $changedLines = self::call('git diff --no-color -U0 -- ' . escapeshellarg($changesService['base'] . $changedFile));
                foreach ($changedLines as $changedLine) {
                    if (!$changedLine) {
                        continue;
                    }
                    if ('-' !== $changedLine[0] && '+' !== $changedLine[0]) {
                        continue;
                    }
                    if (\in_array(substr($changedLine, 0, 3), ['---', '+++'], true)) {
                        continue;
                    }
                    $changedLine = trim(substr($changedLine, 1));
                    if ('' === $changedLine || '*' !== $changedLine[0] ?? null) {
                        $isCommentOnly = false;

                        break 2;
                    }
                }
            }

            yield [$service, $changesService['base'], $isCommentOnly];
            $oneChange = true;
        }
        if (!$oneChange) {
            yield ['', '', false];
        }
    }

    private static function call(string $command): array
    {
        $output = [];
        exec($command . ' 2>&1', $output, $return);
        if (0 !== $return) {
            throw new \Exception('Command "' . $command . '" failed: ' . "\n" . implode("\n", $output));
        }

        return $output;
    }
}
