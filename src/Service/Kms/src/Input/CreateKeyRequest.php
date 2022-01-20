<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\ValueObject\Tag;

final class CreateKeyRequest extends Input
{
    /**
     * The key policy to attach to the KMS key.
     *
     * @var string|null
     */
    private $policy;

    /**
     * A description of the KMS key.
     *
     * @var string|null
     */
    private $description;

    /**
     * Determines the cryptographic operations for which you can use the KMS key. The default value is `ENCRYPT_DECRYPT`.
     * This parameter is required only for asymmetric KMS keys. You can't change the `KeyUsage` value after the KMS key is
     * created.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
     *
     * @var KeyUsageType::*|null
     */
    private $keyUsage;

    /**
     * Instead, use the `KeySpec` parameter.
     *
     * @var CustomerMasterKeySpec::*|null
     */
    private $customerMasterKeySpec;

    /**
     * Specifies the type of KMS key to create. The default value, `SYMMETRIC_DEFAULT`, creates a KMS key with a 256-bit
     * symmetric key for encryption and decryption. For help choosing a key spec for your KMS key, see How to Choose Your
     * KMS key Configuration in the **Key Management Service Developer Guide**.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/symm-asymm-choose.html
     *
     * @var KeySpec::*|null
     */
    private $keySpec;

    /**
     * The source of the key material for the KMS key. You cannot change the origin after you create the KMS key. The
     * default is `AWS_KMS`, which means that KMS creates the key material.
     *
     * @var OriginType::*|null
     */
    private $origin;

    /**
     * Creates the KMS key in the specified custom key store and the key material in its associated CloudHSM cluster. To
     * create a KMS key in a custom key store, you must also specify the `Origin` parameter with a value of `AWS_CLOUDHSM`.
     * The CloudHSM cluster that is associated with the custom key store must have at least two active HSMs, each in a
     * different Availability Zone in the Region.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/custom-key-store-overview.html
     *
     * @var string|null
     */
    private $customKeyStoreId;

    /**
     * A flag to indicate whether to bypass the key policy lockout safety check.
     *
     * @var bool|null
     */
    private $bypassPolicyLockoutSafetyCheck;

    /**
     * Assigns one or more tags to the KMS key. Use this parameter to tag the KMS key when it is created. To tag an existing
     * KMS key, use the TagResource operation.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * Creates a multi-Region primary key that you can replicate into other Amazon Web Services Regions. You cannot change
     * this value after you create the KMS key.
     *
     * @var bool|null
     */
    private $multiRegion;

    /**
     * @param array{
     *   Policy?: string,
     *   Description?: string,
     *   KeyUsage?: KeyUsageType::*,
     *   CustomerMasterKeySpec?: CustomerMasterKeySpec::*,
     *   KeySpec?: KeySpec::*,
     *   Origin?: OriginType::*,
     *   CustomKeyStoreId?: string,
     *   BypassPolicyLockoutSafetyCheck?: bool,
     *   Tags?: Tag[],
     *   MultiRegion?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->policy = $input['Policy'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->keyUsage = $input['KeyUsage'] ?? null;
        $this->customerMasterKeySpec = $input['CustomerMasterKeySpec'] ?? null;
        $this->keySpec = $input['KeySpec'] ?? null;
        $this->origin = $input['Origin'] ?? null;
        $this->customKeyStoreId = $input['CustomKeyStoreId'] ?? null;
        $this->bypassPolicyLockoutSafetyCheck = $input['BypassPolicyLockoutSafetyCheck'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->multiRegion = $input['MultiRegion'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBypassPolicyLockoutSafetyCheck(): ?bool
    {
        return $this->bypassPolicyLockoutSafetyCheck;
    }

    public function getCustomKeyStoreId(): ?string
    {
        return $this->customKeyStoreId;
    }

    /**
     * @deprecated
     *
     * @return CustomerMasterKeySpec::*|null
     */
    public function getCustomerMasterKeySpec(): ?string
    {
        @trigger_error(sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->customerMasterKeySpec;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return KeySpec::*|null
     */
    public function getKeySpec(): ?string
    {
        return $this->keySpec;
    }

    /**
     * @return KeyUsageType::*|null
     */
    public function getKeyUsage(): ?string
    {
        return $this->keyUsage;
    }

    public function getMultiRegion(): ?bool
    {
        return $this->multiRegion;
    }

    /**
     * @return OriginType::*|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getPolicy(): ?string
    {
        return $this->policy;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.CreateKey',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBypassPolicyLockoutSafetyCheck(?bool $value): self
    {
        $this->bypassPolicyLockoutSafetyCheck = $value;

        return $this;
    }

    public function setCustomKeyStoreId(?string $value): self
    {
        $this->customKeyStoreId = $value;

        return $this;
    }

    /**
     * @deprecated
     *
     * @param CustomerMasterKeySpec::*|null $value
     */
    public function setCustomerMasterKeySpec(?string $value): self
    {
        @trigger_error(sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->customerMasterKeySpec = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    /**
     * @param KeySpec::*|null $value
     */
    public function setKeySpec(?string $value): self
    {
        $this->keySpec = $value;

        return $this;
    }

    /**
     * @param KeyUsageType::*|null $value
     */
    public function setKeyUsage(?string $value): self
    {
        $this->keyUsage = $value;

        return $this;
    }

    public function setMultiRegion(?bool $value): self
    {
        $this->multiRegion = $value;

        return $this;
    }

    /**
     * @param OriginType::*|null $value
     */
    public function setOrigin(?string $value): self
    {
        $this->origin = $value;

        return $this;
    }

    public function setPolicy(?string $value): self
    {
        $this->policy = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->policy) {
            $payload['Policy'] = $v;
        }
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->keyUsage) {
            if (!KeyUsageType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "KeyUsage" for "%s". The value "%s" is not a valid "KeyUsageType".', __CLASS__, $v));
            }
            $payload['KeyUsage'] = $v;
        }
        if (null !== $v = $this->customerMasterKeySpec) {
            @trigger_error(sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            if (!CustomerMasterKeySpec::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "CustomerMasterKeySpec" for "%s". The value "%s" is not a valid "CustomerMasterKeySpec".', __CLASS__, $v));
            }
            $payload['CustomerMasterKeySpec'] = $v;
        }
        if (null !== $v = $this->keySpec) {
            if (!KeySpec::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "KeySpec" for "%s". The value "%s" is not a valid "KeySpec".', __CLASS__, $v));
            }
            $payload['KeySpec'] = $v;
        }
        if (null !== $v = $this->origin) {
            if (!OriginType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Origin" for "%s". The value "%s" is not a valid "OriginType".', __CLASS__, $v));
            }
            $payload['Origin'] = $v;
        }
        if (null !== $v = $this->customKeyStoreId) {
            $payload['CustomKeyStoreId'] = $v;
        }
        if (null !== $v = $this->bypassPolicyLockoutSafetyCheck) {
            $payload['BypassPolicyLockoutSafetyCheck'] = (bool) $v;
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->multiRegion) {
            $payload['MultiRegion'] = (bool) $v;
        }

        return $payload;
    }
}
