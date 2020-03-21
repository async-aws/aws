<?php

namespace AsyncAws\Core\Stream;

/**
 * Provides method to convert a input into string or chunks.
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface Stream extends \IteratorAggregate
{
    /**
     * Length in bytes.
     */
    public function length(): ?int;

    public function stringify(): string;
}
