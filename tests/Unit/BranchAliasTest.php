<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Verify BranchAlias values.
 * Rules:
 * - For component without stable release (ie. 0.2.3):
 *   - When the ChangeLog file contains a major change => 0.3-dev.
 *   - When the ChangeLog file contains a minor change => 0.2-dev.
 *   - When the ChangeLog file contains a patch change or no change => 0.2-dev.
 * - For component with stable release (ie. 1.2.3):
 *   - When the ChangeLog file contains a major change => 2.0-dev.
 *   - When the ChangeLog file contains a minor change => 1.3-dev.
 *   - When the ChangeLog file contains a patch change or no change => 1.2-dev.
 */
class BranchAliasTest extends TestCase
{
    /**
     * @dataProvider provideChangedlogFiles
     */
    public function testBranchAliasValue(string $changelogPath)
    {
        $composerPath = \dirname($changelogPath) . '/composer.json';
        self::assertFileExists($composerPath);
        $composer = json_decode(file_get_contents($composerPath), true, \JSON_THROW_ON_ERROR);
        self::assertIsArray($composer);

        // This test assumes the ChangeLog file is well formatted (invalid format should trigger errors in ChangelogTest)
        $lines = explode("\n", file_get_contents($changelogPath));
        switch ($lines[4]) {
            case '### BC-BREAK':
            case '### Removed':
                $level = 0;

                break;
            case '### Added':
            case '### Deprecated':
            case '### Dependency bumped':
                $level = 1;

                break;
            default:
                $level = 2;

                break;
        }

        // We have a major/minor change => we need to know the previous version to guess the next version
        $lastVersion = null;
        foreach (\array_slice($lines, 4) as $line) {
            if (0 === strpos($line, '## ')) {
                $lastVersion = substr($line, 3);

                break;
            }
        }
        self::assertNotNull($lastVersion);
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $lastVersion);

        $parts = explode('.', $lastVersion);
        if (0 === (int) $parts[0]) {
            $level = min(2, $level + 1);
        }

        $parts[$level] = (int) $parts[$level] + 1;
        for ($i = $level + 1; $i < 3; ++$i) {
            $parts[$i] = 0;
        }

        $alias = "{$parts[0]}.{$parts[1]}-dev";
        self::assertSame($alias, $composer['extra']['branch-alias']['dev-master'] ?? null, 'The "' . $composerPath . '" file should contain a "branch-alias" section containing "' . $alias . '"');
    }

    public static function provideChangedlogFiles(): iterable
    {
        $finder = (new Finder())
            ->in(\dirname(__DIR__, 2) . '/src')
            ->name('CHANGELOG.md');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            yield [$file->getRealPath()];
        }
    }
}
