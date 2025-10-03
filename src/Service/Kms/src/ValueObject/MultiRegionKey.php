<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Describes the primary or replica key in a multi-Region key.
 */
final class MultiRegionKey
{
    /**
     * Displays the key ARN of a primary or replica key of a multi-Region key.
     *
     * @var string|null
     */
    private $arn;

    /**
     * Displays the Amazon Web Services Region of a primary or replica key in a multi-Region key.
     *
     * @var string|null
     */
    private $region;

    /**
     * @param array{
     *   Arn?: string|null,
     *   Region?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->region = $input['Region'] ?? null;
    }

    /**
     * @param array{
     *   Arn?: string|null,
     *   Region?: string|null,
     * }|MultiRegionKey $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }
}
