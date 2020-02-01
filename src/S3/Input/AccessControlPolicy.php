<?php

namespace AsyncAws\S3\Input;

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
        foreach ($this->Grants as $item) {
            $item->validate();
        }
        if ($this->Owner) {
            $this->Owner->validate();
        }
    }
}
