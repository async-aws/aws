<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class CreateBucketConfiguration
{
    /**
     * @var string|null
     */
    private $LocationConstraint;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   LocationConstraint?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->LocationConstraint = $input["LocationConstraint"] ?? null;
    }

    public function getLocationConstraint(): ?string
    {
        return $this->LocationConstraint;
    }

    public function setLocationConstraint(?string $value): self
    {
        $this->LocationConstraint = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach ([''] as $name) {
            if (null === $value = $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }

            if (\is_object($value) && method_exists($value, 'validate')) {
                $value->validate();
            }
        }
    }
}
