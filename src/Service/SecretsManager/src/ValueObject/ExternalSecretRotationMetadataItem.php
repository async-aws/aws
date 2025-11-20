<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * The metadata needed to successfully rotate a managed external secret. A list of key value pairs in JSON format
 * specified by the partner. For more information, see Managed external secret partners [^1].
 *
 * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/mes-partners.html
 */
final class ExternalSecretRotationMetadataItem
{
    /**
     * The key that identifies the item.
     *
     * @var string|null
     */
    private $key;

    /**
     * The value of the specified item.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * }|ExternalSecretRotationMetadataItem $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
