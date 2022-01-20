<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Displays the key ARN and Region of the primary key. This field includes the current KMS key if it is the primary key.
 */
final class MultiRegionKey
{
    /**
     * Displays the key ARN of a primary or replica key of a multi-Region key.
     */
    private $arn;

    /**
     * Displays the Amazon Web Services Region of a primary or replica key in a multi-Region key.
     */
    private $region;

    /**
     * @param array{
     *   Arn?: null|string,
     *   Region?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->region = $input['Region'] ?? null;
    }

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
