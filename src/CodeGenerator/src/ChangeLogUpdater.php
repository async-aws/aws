<?php

namespace AsyncAws\CodeGenerator;

/**
 * @internal
 */
final class ChangeLogUpdater
{
    /**
     * @param string[]               $newLines
     * @param callable(string): void $warningReporter
     */
    public function addNewChangeLogLines(string $changeLogPath, string $service, string $sectionLabel, array $newLines, callable $warningReporter): void
    {
        $changeLog = explode("\n", file_get_contents($changeLogPath));
        $nrSection = false;
        $fixSection = false;
        $fixSectionOrder = [
            '### BC-BREAK', '### Removed', // Major
            '### Added', '### Deprecated', '### Dependency bumped', // Minor
            '### Changed', '### Fixed', '### Security', // Patch
        ];
        $fixSectionIndex = array_search($sectionLabel, $fixSectionOrder);
        foreach ($changeLog as $index => $line) {
            if ('## NOT RELEASED' === $line) {
                $nrSection = true;

                continue;
            }
            if (!$nrSection) {
                continue;
            }
            if (str_starts_with($line, '## ')) {
                break;
            }
            if (str_starts_with($line, '### ') && array_search($line, $fixSectionOrder) > $fixSectionIndex) {
                break;
            }

            if ($line === $sectionLabel) {
                $fixSection = true;

                continue;
            }
            if (!$fixSection) {
                continue;
            }

            if ('' !== $line && false !== $index = array_search($line, $newLines, true)) {
                array_splice($newLines, $index, 1);
            }
        }

        if (empty($newLines)) {
            $warningReporter('duplicate entry in CHANGELOG ' . $service);

            return;
        }

        if (!$nrSection) {
            array_splice($changeLog, 2, 0, array_merge([
                '## NOT RELEASED',
                '',
                $sectionLabel,
                '',
            ], $newLines, ['']));
        } elseif (!$fixSection) {
            array_splice($changeLog, $index, 0, array_merge([
                $sectionLabel,
                '',
            ], $newLines, ['']));
        } else {
            array_splice($changeLog, $index - 1, 0, $newLines);
        }
        file_put_contents($changeLogPath, implode("\n", $changeLog));
    }
}
