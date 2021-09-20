<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The API key.
 */
final class ApiKey
{
    /**
     * The API key ID.
     */
    private $id;

    /**
     * A description of the purpose of the API key.
     */
    private $description;

    /**
     * The time after which the API key expires. The date is represented as seconds since the epoch, rounded down to the
     * nearest hour.
     */
    private $expires;

    /**
     * The time after which the API key is deleted. The date is represented as seconds since the epoch, rounded down to the
     * nearest hour.
     */
    private $deletes;

    /**
     * @param array{
     *   id?: null|string,
     *   description?: null|string,
     *   expires?: null|string,
     *   deletes?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['id'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->expires = $input['expires'] ?? null;
        $this->deletes = $input['deletes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeletes(): ?string
    {
        return $this->deletes;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExpires(): ?string
    {
        return $this->expires;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
