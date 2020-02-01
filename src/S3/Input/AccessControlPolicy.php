<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class AccessControlPolicy
{
    /**
     * @var Grant[]
     */
    private $Grants;

    /**
     * @var Owner|null
     */
    private $Owner;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   Grants?: \AsyncAws\S3\Input\Grant[],
     *   Owner?: \AsyncAws\S3\Input\Owner|array,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Grants = array_map(function ($item) {
            return Grant::create($item);
        }, $input["Grants"] ?? []);
        $this->Owner = isset($input["Owner"]) ? Owner::create($input["Owner"]) : null;
    }

    public function getGrants(): array
    {
        return $this->Grants;
    }

    public function setGrants(array $value): self
    {
        $this->Grants = $value;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    public function setOwner(?Owner $value): self
    {
        $this->Owner = $value;

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
