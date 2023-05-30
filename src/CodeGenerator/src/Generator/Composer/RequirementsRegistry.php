<?php

namespace AsyncAws\CodeGenerator\Generator\Composer;

class RequirementsRegistry
{
    private $requirements = [];

    public function addRequirement(string $package, string $version = '*'): void
    {
        if (isset($this->requirements[$package]) && '*' !== $this->requirements[$package]) {
            if ('*' === $version) {
                return; // Keep the existing requirement as it is specific.
            }

            if (version_compare(ltrim($this->requirements[$package], '^'), ltrim($version, '^'), '>')) {
                return; // Keep the existing requirement if it is for a higher version.
            }
        }

        $this->requirements[$package] = $version;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }
}
