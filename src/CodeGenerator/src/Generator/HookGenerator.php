<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Hook;

/**
 * Generate Hook code.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class HookGenerator
{
    /**
     * Generate code for hooks.
     */
    public function generate(Hook $hook, string $input): string
    {
        switch ($hook->getAction()) {
            case 'remove_left':
                return $this->removeLeft($hook->getOptions()['values'] ?? [], $input);
        }

        throw new \RuntimeException('Hook ' . $hook->getAction() . ' is not yet implemented');
    }

    private function removeLeft(array $values, $input): string
    {
        return strtr('VALUE = preg_replace("#^(VALUES)#", "", VALUE);', [
            'VALUES' => implode('|', array_map(function ($value) {
                return preg_quote($value, '#');
            }, $values)),
            'INPUT' => $input,
        ]);
    }
}
