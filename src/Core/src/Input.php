<?php

namespace AsyncAws\Core;

/**
 * Representation of a AWS Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
abstract class Input
{
    /**
     * @var string|null
     */
    public $region;

    /**
     * @var bool
     */
    private $responseBuffer = true;

    /**
     * @param array{'@region'?: ?string, '@responseBuffer'?: bool, ...} $input
     */
    protected function __construct(array $input)
    {
        $this->region = $input['@region'] ?? null;
        $this->responseBuffer = $input['@responseBuffer'] ?? true;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function shouldBufferResponse(): bool
    {
        return $this->responseBuffer;
    }

    abstract public function request(): Request;
}
