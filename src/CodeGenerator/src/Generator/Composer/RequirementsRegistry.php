<?php

namespace AsyncAws\CodeGenerator\Generator\Composer;

class RequirementsRegistry
{
    private $requirements = [];

    public function addRequirement(string $package, string $version = '*'): void
    {
        $this->requirements[$package] = $version;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }
}
