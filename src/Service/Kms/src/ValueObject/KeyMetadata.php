<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\ExpirationModelType;
use AsyncAws\Kms\Enum\KeyManagerType;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyState;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

/**
 * Metadata associated with the KMS key.
 */
final class KeyMetadata
{
    /**
     * The twelve-digit account ID of the Amazon Web Services account that owns the KMS key.
     */
    private $awsAccountId;

    /**
     * The globally unique identifier for the KMS key.
     */
    private $keyId;

    /**
     * The Amazon Resource Name (ARN) of the KMS key. For examples, see Key Management Service (KMS) in the Example ARNs
     * section of the *Amazon Web Services General Reference*.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kms
     */
    private $arn;

    /**
     * The date and time when the KMS key was created.
     */
    private $creationDate;

    /**
     * Specifies whether the KMS key is enabled. When `KeyState` is `Enabled` this value is true, otherwise it is false.
     */
    private $enabled;

    /**
     * The description of the KMS key.
     */
    private $description;

    /**
     * The cryptographic operations for which you can use the KMS key.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
     */
    private $keyUsage;

    /**
     * The current status of the KMS key.
     */
    private $keyState;

    /**
     * The date and time after which KMS deletes this KMS key. This value is present only when the KMS key is scheduled for
     * deletion, that is, when its `KeyState` is `PendingDeletion`.
     */
    private $deletionDate;

    /**
     * The time at which the imported key material expires. When the key material expires, KMS deletes the key material and
     * the KMS key becomes unusable. This value is present only for KMS keys whose `Origin` is `EXTERNAL` and whose
     * `ExpirationModel` is `KEY_MATERIAL_EXPIRES`, otherwise this value is omitted.
     */
    private $validTo;

    /**
     * The source of the key material for the KMS key. When this value is `AWS_KMS`, KMS created the key material. When this
     * value is `EXTERNAL`, the key material was imported or the KMS key doesn't have any key material. When this value is
     * `AWS_CLOUDHSM`, the key material was created in the CloudHSM cluster associated with a custom key store.
     */
    private $origin;

    /**
     * A unique identifier for the custom key store that contains the KMS key. This value is present only when the KMS key
     * is created in a custom key store.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/custom-key-store-overview.html
     */
    private $customKeyStoreId;

    /**
     * The cluster ID of the CloudHSM cluster that contains the key material for the KMS key. When you create a KMS key in a
     * custom key store, KMS creates the key material for the KMS key in the associated CloudHSM cluster. This value is
     * present only when the KMS key is created in a custom key store.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/custom-key-store-overview.html
     */
    private $cloudHsmClusterId;

    /**
     * Specifies whether the KMS key's key material expires. This value is present only when `Origin` is `EXTERNAL`,
     * otherwise this value is omitted.
     */
    private $expirationModel;

    /**
     * The manager of the KMS key. KMS keys in your Amazon Web Services account are either customer managed or Amazon Web
     * Services managed. For more information about the difference, see KMS keys in the *Key Management Service Developer
     * Guide*.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#kms_keys
     */
    private $keyManager;

    /**
     * Instead, use the `KeySpec` field.
     */
    private $customerMasterKeySpec;

    /**
     * Describes the type of key material in the KMS key.
     */
    private $keySpec;

    /**
     * The encryption algorithms that the KMS key supports. You cannot use the KMS key with other encryption algorithms
     * within KMS.
     */
    private $encryptionAlgorithms;

    /**
     * The signing algorithms that the KMS key supports. You cannot use the KMS key with other signing algorithms within
     * KMS.
     */
    private $signingAlgorithms;

    /**
     * Indicates whether the KMS key is a multi-Region (`True`) or regional (`False`) key. This value is `True` for
     * multi-Region primary and replica keys and `False` for regional KMS keys.
     */
    private $multiRegion;

    /**
     * Lists the primary and replica keys in same multi-Region key. This field is present only when the value of the
     * `MultiRegion` field is `True`.
     */
    private $multiRegionConfiguration;

    /**
     * The waiting period before the primary key in a multi-Region key is deleted. This waiting period begins when the last
     * of its replica keys is deleted. This value is present only when the `KeyState` of the KMS key is
     * `PendingReplicaDeletion`. That indicates that the KMS key is the primary key in a multi-Region key, it is scheduled
     * for deletion, and it still has existing replica keys.
     */
    private $pendingDeletionWindowInDays;

    /**
     * @param array{
     *   AWSAccountId?: null|string,
     *   KeyId: string,
     *   Arn?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   Enabled?: null|bool,
     *   Description?: null|string,
     *   KeyUsage?: null|KeyUsageType::*,
     *   KeyState?: null|KeyState::*,
     *   DeletionDate?: null|\DateTimeImmutable,
     *   ValidTo?: null|\DateTimeImmutable,
     *   Origin?: null|OriginType::*,
     *   CustomKeyStoreId?: null|string,
     *   CloudHsmClusterId?: null|string,
     *   ExpirationModel?: null|ExpirationModelType::*,
     *   KeyManager?: null|KeyManagerType::*,
     *   CustomerMasterKeySpec?: null|CustomerMasterKeySpec::*,
     *   KeySpec?: null|KeySpec::*,
     *   EncryptionAlgorithms?: null|list<EncryptionAlgorithmSpec::*>,
     *   SigningAlgorithms?: null|list<SigningAlgorithmSpec::*>,
     *   MultiRegion?: null|bool,
     *   MultiRegionConfiguration?: null|MultiRegionConfiguration|array,
     *   PendingDeletionWindowInDays?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->awsAccountId = $input['AWSAccountId'] ?? null;
        $this->keyId = $input['KeyId'] ?? null;
        $this->arn = $input['Arn'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->enabled = $input['Enabled'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->keyUsage = $input['KeyUsage'] ?? null;
        $this->keyState = $input['KeyState'] ?? null;
        $this->deletionDate = $input['DeletionDate'] ?? null;
        $this->validTo = $input['ValidTo'] ?? null;
        $this->origin = $input['Origin'] ?? null;
        $this->customKeyStoreId = $input['CustomKeyStoreId'] ?? null;
        $this->cloudHsmClusterId = $input['CloudHsmClusterId'] ?? null;
        $this->expirationModel = $input['ExpirationModel'] ?? null;
        $this->keyManager = $input['KeyManager'] ?? null;
        $this->customerMasterKeySpec = $input['CustomerMasterKeySpec'] ?? null;
        $this->keySpec = $input['KeySpec'] ?? null;
        $this->encryptionAlgorithms = $input['EncryptionAlgorithms'] ?? null;
        $this->signingAlgorithms = $input['SigningAlgorithms'] ?? null;
        $this->multiRegion = $input['MultiRegion'] ?? null;
        $this->multiRegionConfiguration = isset($input['MultiRegionConfiguration']) ? MultiRegionConfiguration::create($input['MultiRegionConfiguration']) : null;
        $this->pendingDeletionWindowInDays = $input['PendingDeletionWindowInDays'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getAwsAccountId(): ?string
    {
        return $this->awsAccountId;
    }

    public function getCloudHsmClusterId(): ?string
    {
        return $this->cloudHsmClusterId;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
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

    public function getDeletionDate(): ?\DateTimeImmutable
    {
        return $this->deletionDate;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @return list<EncryptionAlgorithmSpec::*>
     */
    public function getEncryptionAlgorithms(): array
    {
        return $this->encryptionAlgorithms ?? [];
    }

    /**
     * @return ExpirationModelType::*|null
     */
    public function getExpirationModel(): ?string
    {
        return $this->expirationModel;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    /**
     * @return KeyManagerType::*|null
     */
    public function getKeyManager(): ?string
    {
        return $this->keyManager;
    }

    /**
     * @return KeySpec::*|null
     */
    public function getKeySpec(): ?string
    {
        return $this->keySpec;
    }

    /**
     * @return KeyState::*|null
     */
    public function getKeyState(): ?string
    {
        return $this->keyState;
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

    public function getMultiRegionConfiguration(): ?MultiRegionConfiguration
    {
        return $this->multiRegionConfiguration;
    }

    /**
     * @return OriginType::*|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getPendingDeletionWindowInDays(): ?int
    {
        return $this->pendingDeletionWindowInDays;
    }

    /**
     * @return list<SigningAlgorithmSpec::*>
     */
    public function getSigningAlgorithms(): array
    {
        return $this->signingAlgorithms ?? [];
    }

    public function getValidTo(): ?\DateTimeImmutable
    {
        return $this->validTo;
    }
}
