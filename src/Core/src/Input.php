<?php

namespace AsyncAws\Core;

/**
 * Representation of a AWS Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
abstract class Input
{
    public $region;

    /**
     * @param array{
     *   @region?: ?string,
     * } $input
     */
    protected function __construct(array $input)
    {
        $this->region = $input['@region'] ?? null;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    abstract public function request(): Request;
}
