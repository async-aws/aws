<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Information about the external key [^1]that is associated with a KMS key in an external key store.
 *
 * This element appears in a CreateKey or DescribeKey response only for a KMS key in an external key store.
 *
 * The *external key* is a symmetric encryption key that is hosted by an external key manager outside of Amazon Web
 * Services. When you use the KMS key in an external key store in a cryptographic operation, the cryptographic operation
 * is performed in the external key manager using the specified external key. For more information, see External key
 * [^2] in the *Key Management Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-external-key
 * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-external-key
 */
final class XksKeyConfigurationType
{
    /**
     * The ID of the external key in its external key manager. This is the ID that the external key store proxy uses to
     * identify the external key.
     *
     * @var string|null
     */
    private $id;

    /**
     * @param array{
     *   Id?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
    }

    /**
     * @param array{
     *   Id?: string|null,
     * }|XksKeyConfigurationType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
