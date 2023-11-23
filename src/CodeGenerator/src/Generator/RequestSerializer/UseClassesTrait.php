<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

/**
 * @internal
 */
trait UseClassesTrait
{
    /**
     * @var list<array<string, true>>
     */
    private $usedClassesStack = [];

    private function usedClassesInit(): void
    {
        $this->usedClassesStack[] = [];
    }

    private function usedClassesAdd(string $className): void
    {
        $this->usedClassesStack[\count($this->usedClassesStack) - 1][$className] = true;
    }

    /**
     * @return list<string>
     */
    private function usedClassesFlush(): array
    {
        return array_keys(array_pop($this->usedClassesStack));
    }
}
