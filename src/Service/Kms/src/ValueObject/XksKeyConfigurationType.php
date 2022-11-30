<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Information about the external key that is associated with a KMS key in an external key store.
 * For more information, see External key in the *Key Management Service Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-external-key
 */
final class XksKeyConfigurationType
{
    /**
     * The ID of the external key in its external key manager. This is the ID that the external key store proxy uses to
     * identify the external key.
     */
    private $id;

    /**
     * @param array{
     *   Id?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
