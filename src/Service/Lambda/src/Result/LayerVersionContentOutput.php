<?php

namespace AsyncAws\Lambda\Result;

class LayerVersionContentOutput
{
    /**
     * A link to the layer archive in Amazon S3 that is valid for 10 minutes.
     */
    private $Location;

    /**
     * The SHA-256 hash of the layer archive.
     */
    private $CodeSha256;

    /**
     * The size of the layer archive in bytes.
     */
    private $CodeSize;

    /**
     * @param array{
     *   Location: ?string,
     *   CodeSha256: ?string,
     *   CodeSize: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Location = $input['Location'];
        $this->CodeSha256 = $input['CodeSha256'];
        $this->CodeSize = $input['CodeSize'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeSha256(): ?string
    {
        return $this->CodeSha256;
    }

    public function getCodeSize(): ?string
    {
        return $this->CodeSize;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }
}
