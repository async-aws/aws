<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Describes an API key.
 * Customers invoke AppSync GraphQL API operations with API keys as an identity mechanism. There are two key versions:
 * **da1**: We introduced this version at launch in November 2017. These keys always expire after 7 days. Amazon
 * DynamoDB TTL manages key expiration. These keys ceased to be valid after February 21, 2018, and they should no longer
 * be used.
 *
 * - `ListApiKeys` returns the expiration time in milliseconds.
 * - `CreateApiKey` returns the expiration time in milliseconds.
 * - `UpdateApiKey` is not available for this key version.
 * - `DeleteApiKey` deletes the item from the table.
 * - Expiration is stored in DynamoDB as milliseconds. This results in a bug where keys are not automatically deleted
 *   because DynamoDB expects the TTL to be stored in seconds. As a one-time action, we deleted these keys from the
 *   table on February 21, 2018.
 *
 * **da2**: We introduced this version in February 2018 when AppSync added support to extend key expiration.
 *
 * - `ListApiKeys` returns the expiration time and deletion time in seconds.
 * - `CreateApiKey` returns the expiration time and deletion time in seconds and accepts a user-provided expiration time
 *   in seconds.
 * - `UpdateApiKey` returns the expiration time and and deletion time in seconds and accepts a user-provided expiration
 *   time in seconds. Expired API keys are kept for 60 days after the expiration time. You can update the key expiration
 *   time as long as the key isn't deleted.
 * - `DeleteApiKey` deletes the item from the table.
 * - Expiration is stored in DynamoDB as seconds. After the expiration time, using the key to authenticate will fail.
 *   However, you can reinstate the key before deletion.
 * - Deletion is stored in DynamoDB as seconds. The key is deleted after deletion time.
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
