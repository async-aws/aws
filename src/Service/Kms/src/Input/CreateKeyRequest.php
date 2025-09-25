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
     * If you provide a key policy, it must meet the following criteria:
     *
     * - The key policy must allow the calling principal to make a subsequent `PutKeyPolicy` request on the KMS key. This
     *   reduces the risk that the KMS key becomes unmanageable. For more information, see Default key policy [^1] in the
     *   *Key Management Service Developer Guide*. (To omit this condition, set `BypassPolicyLockoutSafetyCheck` to true.)
     * - Each statement in the key policy must contain one or more principals. The principals in the key policy must exist
     *   and be visible to KMS. When you create a new Amazon Web Services principal, you might need to enforce a delay
     *   before including the new principal in a key policy because the new principal might not be immediately visible to
     *   KMS. For more information, see Changes that I make are not always immediately visible [^2] in the *Amazon Web
     *   Services Identity and Access Management User Guide*.
     *
     * > If either of the required `Resource` or `Action` elements are missing from a key policy statement, the policy
     * > statement has no effect. When a key policy statement is missing one of these elements, the KMS console correctly
     * > reports an error, but the `CreateKey` and `PutKeyPolicy` API requests succeed, even though the policy statement is
     * > ineffective.
     * >
     * > For more information on required key policy elements, see Elements in a key policy [^3] in the *Key Management
     * > Service Developer Guide*.
     *
     * If you do not provide a key policy, KMS attaches a default key policy to the KMS key. For more information, see
     * Default key policy [^4] in the *Key Management Service Developer Guide*.
     *
     * > If the key policy exceeds the length constraint, KMS returns a `LimitExceededException`.
     *
     * For help writing and formatting a JSON policy document, see the IAM JSON Policy Reference [^5] in the **Identity and
     * Access Management User Guide**.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-policy-default.html#prevent-unmanageable-key
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/troubleshoot_general.html#troubleshoot_general_eventual-consistency
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/key-policy-overview.html#key-policy-elements
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/key-policy-default.html
     * [^5]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_policies.html
     *
     * @var string|null
     */
    private $policy;

    /**
     * A description of the KMS key. Use a description that helps you decide whether the KMS key is appropriate for a task.
     * The default value is an empty string (no description).
     *
     * ! Do not include confidential or sensitive information in this field. This field may be displayed in plaintext in
     * ! CloudTrail logs and other output.
     *
     * To set or change the description after the key is created, use UpdateKeyDescription.
     *
     * @var string|null
     */
    private $description;

    /**
     * Determines the cryptographic operations [^1] for which you can use the KMS key. The default value is
     * `ENCRYPT_DECRYPT`. This parameter is optional when you are creating a symmetric encryption KMS key; otherwise, it is
     * required. You can't change the `KeyUsage` [^2] value after the KMS key is created. Each KMS key can have only one key
     * usage. This follows key usage best practices according to NIST SP 800-57 Recommendations for Key Management [^3],
     * section 5.2, Key usage.
     *
     * Select only one valid value.
     *
     * - For symmetric encryption KMS keys, omit the parameter or specify `ENCRYPT_DECRYPT`.
     * - For HMAC KMS keys (symmetric), specify `GENERATE_VERIFY_MAC`.
     * - For asymmetric KMS keys with RSA key pairs, specify `ENCRYPT_DECRYPT` or `SIGN_VERIFY`.
     * - For asymmetric KMS keys with NIST-recommended elliptic curve key pairs, specify `SIGN_VERIFY` or `KEY_AGREEMENT`.
     * - For asymmetric KMS keys with `ECC_SECG_P256K1` key pairs, specify `SIGN_VERIFY`.
     * - For asymmetric KMS keys with ML-DSA key pairs, specify `SIGN_VERIFY`.
     * - For asymmetric KMS keys with SM2 key pairs (China Regions only), specify `ENCRYPT_DECRYPT`, `SIGN_VERIFY`, or
     *   `KEY_AGREEMENT`.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-cryptography.html#cryptographic-operations
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/create-keys.html#key-usage
     * [^3]: https://csrc.nist.gov/pubs/sp/800/57/pt1/r5/final
     *
     * @var KeyUsageType::*|null
     */
    private $keyUsage;

    /**
     * Instead, use the `KeySpec` parameter.
     *
     * The `KeySpec` and `CustomerMasterKeySpec` parameters work the same way. Only the names differ. We recommend that you
     * use `KeySpec` parameter in your code. However, to avoid breaking changes, KMS supports both parameters.
     *
     * @var CustomerMasterKeySpec::*|null
     */
    private $customerMasterKeySpec;

    /**
     * Specifies the type of KMS key to create. The default value, `SYMMETRIC_DEFAULT`, creates a KMS key with a 256-bit
     * AES-GCM key that is used for encryption and decryption, except in China Regions, where it creates a 128-bit symmetric
     * key that uses SM4 encryption. For a detailed description of all supported key specs, see Key spec reference [^1] in
     * the **Key Management Service Developer Guide**.
     *
     * The `KeySpec` determines whether the KMS key contains a symmetric key or an asymmetric key pair. It also determines
     * the algorithms that the KMS key supports. You can't change the `KeySpec` after the KMS key is created. To further
     * restrict the algorithms that can be used with the KMS key, use a condition key in its key policy or IAM policy. For
     * more information, see kms:EncryptionAlgorithm [^2], kms:MacAlgorithm [^3], kms:KeyAgreementAlgorithm [^4], or
     * kms:SigningAlgorithm [^5] in the **Key Management Service Developer Guide**.
     *
     * ! Amazon Web Services services that are integrated with KMS [^6] use symmetric encryption KMS keys to protect your
     * ! data. These services do not support asymmetric KMS keys or HMAC KMS keys.
     *
     * KMS supports the following key specs for KMS keys:
     *
     * - Symmetric encryption key (default)
     *
     *   - `SYMMETRIC_DEFAULT`
     *
     * - HMAC keys (symmetric)
     *
     *   - `HMAC_224`
     *   - `HMAC_256`
     *   - `HMAC_384`
     *   - `HMAC_512`
     *
     * - Asymmetric RSA key pairs (encryption and decryption -or- signing and verification)
     *
     *   - `RSA_2048`
     *   - `RSA_3072`
     *   - `RSA_4096`
     *
     * - Asymmetric NIST-recommended elliptic curve key pairs (signing and verification -or- deriving shared secrets)
     *
     *   - `ECC_NIST_P256` (secp256r1)
     *   - `ECC_NIST_P384` (secp384r1)
     *   - `ECC_NIST_P521` (secp521r1)
     *
     * - Other asymmetric elliptic curve key pairs (signing and verification)
     *
     *   - `ECC_SECG_P256K1` (secp256k1), commonly used for cryptocurrencies.
     *
     * - Asymmetric ML-DSA key pairs (signing and verification)
     *
     *   - `ML_DSA_44`
     *   - `ML_DSA_65`
     *   - `ML_DSA_87`
     *
     * - SM2 key pairs (encryption and decryption -or- signing and verification -or- deriving shared secrets)
     *
     *   - `SM2` (China Regions only)
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/symm-asymm-choose-key-spec.html
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/conditions-kms.html#conditions-kms-encryption-algorithm
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/conditions-kms.html#conditions-kms-mac-algorithm
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/conditions-kms.html#conditions-kms-key-agreement-algorithm
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/conditions-kms.html#conditions-kms-signing-algorithm
     * [^6]: http://aws.amazon.com/kms/features/#AWS_Service_Integration
     *
     * @var KeySpec::*|null
     */
    private $keySpec;

    /**
     * The source of the key material for the KMS key. You cannot change the origin after you create the KMS key. The
     * default is `AWS_KMS`, which means that KMS creates the key material.
     *
     * To create a KMS key with no key material [^1] (for imported key material), set this value to `EXTERNAL`. For more
     * information about importing key material into KMS, see Importing Key Material [^2] in the *Key Management Service
     * Developer Guide*. The `EXTERNAL` origin value is valid only for symmetric KMS keys.
     *
     * To create a KMS key in an CloudHSM key store [^3] and create its key material in the associated CloudHSM cluster, set
     * this value to `AWS_CLOUDHSM`. You must also use the `CustomKeyStoreId` parameter to identify the CloudHSM key store.
     * The `KeySpec` value must be `SYMMETRIC_DEFAULT`.
     *
     * To create a KMS key in an external key store [^4], set this value to `EXTERNAL_KEY_STORE`. You must also use the
     * `CustomKeyStoreId` parameter to identify the external key store and the `XksKeyId` parameter to identify the
     * associated external key. The `KeySpec` value must be `SYMMETRIC_DEFAULT`.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys-create-cmk.html
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/create-cmk-keystore.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/create-xks-keys.html
     *
     * @var OriginType::*|null
     */
    private $origin;

    /**
     * Creates the KMS key in the specified custom key store [^1]. The `ConnectionState` of the custom key store must be
     * `CONNECTED`. To find the CustomKeyStoreID and ConnectionState use the DescribeCustomKeyStores operation.
     *
     * This parameter is valid only for symmetric encryption KMS keys in a single Region. You cannot create any other type
     * of KMS key in a custom key store.
     *
     * When you create a KMS key in an CloudHSM key store, KMS generates a non-exportable 256-bit symmetric key in its
     * associated CloudHSM cluster and associates it with the KMS key. When you create a KMS key in an external key store,
     * you must use the `XksKeyId` parameter to specify an external key that serves as key material for the KMS key.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-store-overview.html
     *
     * @var string|null
     */
    private $customKeyStoreId;

    /**
     * Skips ("bypasses") the key policy lockout safety check. The default value is false.
     *
     * ! Setting this value to true increases the risk that the KMS key becomes unmanageable. Do not set this value to true
     * ! indiscriminately.
     * !
     * ! For more information, see Default key policy [^1] in the *Key Management Service Developer Guide*.
     *
     * Use this parameter only when you intend to prevent the principal that is making the request from making a subsequent
     * PutKeyPolicy [^2] request on the KMS key.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-policy-default.html#prevent-unmanageable-key
     * [^2]: https://docs.aws.amazon.com/kms/latest/APIReference/API_PutKeyPolicy.html
     *
     * @var bool|null
     */
    private $bypassPolicyLockoutSafetyCheck;

    /**
     * Assigns one or more tags to the KMS key. Use this parameter to tag the KMS key when it is created. To tag an existing
     * KMS key, use the TagResource operation.
     *
     * ! Do not include confidential or sensitive information in this field. This field may be displayed in plaintext in
     * ! CloudTrail logs and other output.
     *
     * > Tagging or untagging a KMS key can allow or deny permission to the KMS key. For details, see ABAC for KMS [^1] in
     * > the *Key Management Service Developer Guide*.
     *
     * To use this parameter, you must have kms:TagResource [^2] permission in an IAM policy.
     *
     * Each tag consists of a tag key and a tag value. Both the tag key and the tag value are required, but the tag value
     * can be an empty (null) string. You cannot have more than one tag on a KMS key with the same tag key. If you specify
     * an existing tag key with a different tag value, KMS replaces the current tag value with the specified one.
     *
     * When you add tags to an Amazon Web Services resource, Amazon Web Services generates a cost allocation report with
     * usage and costs aggregated by tags. Tags can also be used to control access to a KMS key. For details, see Tags in
     * KMS [^3].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/abac.html
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/tagging-keys.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * Creates a multi-Region primary key that you can replicate into other Amazon Web Services Regions. You cannot change
     * this value after you create the KMS key.
     *
     * For a multi-Region key, set this parameter to `True`. For a single-Region KMS key, omit this parameter or set it to
     * `False`. The default value is `False`.
     *
     * This operation supports *multi-Region keys*, an KMS feature that lets you create multiple interoperable KMS keys in
     * different Amazon Web Services Regions. Because these KMS keys have the same key ID, key material, and other metadata,
     * you can use them interchangeably to encrypt data in one Amazon Web Services Region and decrypt it in a different
     * Amazon Web Services Region without re-encrypting the data or making a cross-Region call. For more information about
     * multi-Region keys, see Multi-Region keys in KMS [^1] in the *Key Management Service Developer Guide*.
     *
     * This value creates a *primary key*, not a replica. To create a *replica key*, use the ReplicateKey operation.
     *
     * You can create a symmetric or asymmetric multi-Region key, and you can create a multi-Region key with imported key
     * material. However, you cannot create a multi-Region key in a custom key store.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/multi-region-keys-overview.html
     *
     * @var bool|null
     */
    private $multiRegion;

    /**
     * Identifies the external key [^1] that serves as key material for the KMS key in an external key store [^2]. Specify
     * the ID that the external key store proxy [^3] uses to refer to the external key. For help, see the documentation for
     * your external key store proxy.
     *
     * This parameter is required for a KMS key with an `Origin` value of `EXTERNAL_KEY_STORE`. It is not valid for KMS keys
     * with any other `Origin` value.
     *
     * The external key must be an existing 256-bit AES symmetric encryption key hosted outside of Amazon Web Services in an
     * external key manager associated with the external key store specified by the `CustomKeyStoreId` parameter. This key
     * must be enabled and configured to perform encryption and decryption. Each KMS key in an external key store must use a
     * different external key. For details, see Requirements for a KMS key in an external key store [^4] in the *Key
     * Management Service Developer Guide*.
     *
     * Each KMS key in an external key store is associated two backing keys. One is key material that KMS generates. The
     * other is the external key specified by this parameter. When you use the KMS key in an external key store to encrypt
     * data, the encryption operation is performed first by KMS using the KMS key material, and then by the external key
     * manager using the specified external key, a process known as *double encryption*. For details, see Double encryption
     * [^5] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-external-key
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-xks-proxy
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/create-xks-keys.html#xks-key-requirements
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-double-encryption
     *
     * @var string|null
     */
    private $xksKeyId;

    /**
     * @param array{
     *   Policy?: null|string,
     *   Description?: null|string,
     *   KeyUsage?: null|KeyUsageType::*,
     *   CustomerMasterKeySpec?: null|CustomerMasterKeySpec::*,
     *   KeySpec?: null|KeySpec::*,
     *   Origin?: null|OriginType::*,
     *   CustomKeyStoreId?: null|string,
     *   BypassPolicyLockoutSafetyCheck?: null|bool,
     *   Tags?: null|array<Tag|array>,
     *   MultiRegion?: null|bool,
     *   XksKeyId?: null|string,
     *   '@region'?: string|null,
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
        $this->xksKeyId = $input['XksKeyId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Policy?: null|string,
     *   Description?: null|string,
     *   KeyUsage?: null|KeyUsageType::*,
     *   CustomerMasterKeySpec?: null|CustomerMasterKeySpec::*,
     *   KeySpec?: null|KeySpec::*,
     *   Origin?: null|OriginType::*,
     *   CustomKeyStoreId?: null|string,
     *   BypassPolicyLockoutSafetyCheck?: null|bool,
     *   Tags?: null|array<Tag|array>,
     *   MultiRegion?: null|bool,
     *   XksKeyId?: null|string,
     *   '@region'?: string|null,
     * }|CreateKeyRequest $input
     */
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
        @trigger_error(\sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

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

    public function getXksKeyId(): ?string
    {
        return $this->xksKeyId;
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
            'Accept' => 'application/json',
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
        @trigger_error(\sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
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

    public function setXksKeyId(?string $value): self
    {
        $this->xksKeyId = $value;

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
                throw new InvalidArgument(\sprintf('Invalid parameter "KeyUsage" for "%s". The value "%s" is not a valid "KeyUsageType".', __CLASS__, $v));
            }
            $payload['KeyUsage'] = $v;
        }
        if (null !== $v = $this->customerMasterKeySpec) {
            @trigger_error(\sprintf('The property "CustomerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            if (!CustomerMasterKeySpec::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "CustomerMasterKeySpec" for "%s". The value "%s" is not a valid "CustomerMasterKeySpec".', __CLASS__, $v));
            }
            $payload['CustomerMasterKeySpec'] = $v;
        }
        if (null !== $v = $this->keySpec) {
            if (!KeySpec::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "KeySpec" for "%s". The value "%s" is not a valid "KeySpec".', __CLASS__, $v));
            }
            $payload['KeySpec'] = $v;
        }
        if (null !== $v = $this->origin) {
            if (!OriginType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Origin" for "%s". The value "%s" is not a valid "OriginType".', __CLASS__, $v));
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
        if (null !== $v = $this->xksKeyId) {
            $payload['XksKeyId'] = $v;
        }

        return $payload;
    }
}
