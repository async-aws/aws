<?php

namespace AsyncAws\S3\ValueObject;

class AccessControlPolicy
{
    /**
     * A list of grants.
     */
    private $Grants;

    /**
     * Container for the bucket owner's display name and ID.
     */
    private $Owner;

    /**
     * @param array{
     *   Grants?: null|\AsyncAws\S3\ValueObject\Grant[],
     *   Owner?: null|\AsyncAws\S3\ValueObject\Owner|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Grants = array_map(function ($item) { return Grant::create($item); }, $input['Grants'] ?? []);
        $this->Owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Grant[]
     */
    public function getGrants(): array
    {
        return $this->Grants;
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    public function validate(): void
    {
        foreach ($this->Grants as $item) {
            $item->validate();
        }

        if (null !== $this->Owner) {
            $this->Owner->validate();
        }
    }
}
