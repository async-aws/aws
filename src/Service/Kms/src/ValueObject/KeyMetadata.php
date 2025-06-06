<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\ExpirationModelType;
use AsyncAws\Kms\Enum\KeyAgreementAlgorithmSpec;
use AsyncAws\Kms\Enum\KeyManagerType;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyState;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\MacAlgorithmSpec;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

/**
 * Contains metadata about a KMS key.
 *
 * This data type is used as a response element for the CreateKey, DescribeKey, and ReplicateKey operations.
 */
final class KeyMetadata
{
    /**
     * The twelve-digit account ID of the Amazon Web Services account that owns the KMS key.
     *
     * @var string|null
     */
    private $awsAccountId;

    /**
     * The globally unique identifier for the KMS key.
     *
     * @var string
     */
    private $keyId;

    /**
     * The Amazon Resource Name (ARN) of the KMS key. For examples, see Key Management Service (KMS) [^1] in the Example
     * ARNs section of the *Amazon Web Services General Reference*.
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kms
     *
     * @var string|null
     */
    private $arn;

    /**
     * The date and time when the KMS key was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * Specifies whether the KMS key is enabled. When `KeyState` is `Enabled` this value is true, otherwise it is false.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * The description of the KMS key.
     *
     * @var string|null
     */
    private $description;

    /**
     * The cryptographic operations [^1] for which you can use the KMS key.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-cryptography.html#cryptographic-operations
     *
     * @var KeyUsageType::*|null
     */
    private $keyUsage;

    /**
     * The current status of the KMS key.
     *
     * For more information about how key state affects the use of a KMS key, see Key states of KMS keys [^1] in the *Key
     * Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     *
     * @var KeyState::*|null
     */
    private $keyState;

    /**
     * The date and time after which KMS deletes this KMS key. This value is present only when the KMS key is scheduled for
     * deletion, that is, when its `KeyState` is `PendingDeletion`.
     *
     * When the primary key in a multi-Region key is scheduled for deletion but still has replica keys, its key state is
     * `PendingReplicaDeletion` and the length of its waiting period is displayed in the `PendingDeletionWindowInDays`
     * field.
     *
     * @var \DateTimeImmutable|null
     */
    private $deletionDate;

    /**
     * The earliest time at which any imported key material permanently associated with this KMS key expires. When a key
     * material expires, KMS deletes the key material and the KMS key becomes unusable. This value is present only for KMS
     * keys whose `Origin` is `EXTERNAL` and the `ExpirationModel` is `KEY_MATERIAL_EXPIRES`, otherwise this value is
     * omitted.
     *
     * @var \DateTimeImmutable|null
     */
    private $validTo;

    /**
     * The source of the key material for the KMS key. When this value is `AWS_KMS`, KMS created the key material. When this
     * value is `EXTERNAL`, the key material was imported or the KMS key doesn't have any key material. When this value is
     * `AWS_CLOUDHSM`, the key material was created in the CloudHSM cluster associated with a custom key store.
     *
     * @var OriginType::*|null
     */
    private $origin;

    /**
     * A unique identifier for the custom key store [^1] that contains the KMS key. This field is present only when the KMS
     * key is created in a custom key store.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-store-overview.html
     *
     * @var string|null
     */
    private $customKeyStoreId;

    /**
     * The cluster ID of the CloudHSM cluster that contains the key material for the KMS key. When you create a KMS key in
     * an CloudHSM custom key store [^1], KMS creates the key material for the KMS key in the associated CloudHSM cluster.
     * This field is present only when the KMS key is created in an CloudHSM key store.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-store-overview.html
     *
     * @var string|null
     */
    private $cloudHsmClusterId;

    /**
     * Specifies whether the KMS key's key material expires. This value is present only when `Origin` is `EXTERNAL`,
     * otherwise this value is omitted.
     *
     * @var ExpirationModelType::*|null
     */
    private $expirationModel;

    /**
     * The manager of the KMS key. KMS keys in your Amazon Web Services account are either customer managed or Amazon Web
     * Services managed. For more information about the difference, see KMS keys [^1] in the *Key Management Service
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#kms_keys
     *
     * @var KeyManagerType::*|null
     */
    private $keyManager;

    /**
     * Instead, use the `KeySpec` field.
     *
     * The `KeySpec` and `CustomerMasterKeySpec` fields have the same value. We recommend that you use the `KeySpec` field
     * in your code. However, to avoid breaking changes, KMS supports both fields.
     *
     * @var CustomerMasterKeySpec::*|null
     */
    private $customerMasterKeySpec;

    /**
     * Describes the type of key material in the KMS key.
     *
     * @var KeySpec::*|null
     */
    private $keySpec;

    /**
     * The encryption algorithms that the KMS key supports. You cannot use the KMS key with other encryption algorithms
     * within KMS.
     *
     * This value is present only when the `KeyUsage` of the KMS key is `ENCRYPT_DECRYPT`.
     *
     * @var list<EncryptionAlgorithmSpec::*>|null
     */
    private $encryptionAlgorithms;

    /**
     * The signing algorithms that the KMS key supports. You cannot use the KMS key with other signing algorithms within
     * KMS.
     *
     * This field appears only when the `KeyUsage` of the KMS key is `SIGN_VERIFY`.
     *
     * @var list<SigningAlgorithmSpec::*>|null
     */
    private $signingAlgorithms;

    /**
     * The key agreement algorithm used to derive a shared secret.
     *
     * @var list<KeyAgreementAlgorithmSpec::*>|null
     */
    private $keyAgreementAlgorithms;

    /**
     * Indicates whether the KMS key is a multi-Region (`True`) or regional (`False`) key. This value is `True` for
     * multi-Region primary and replica keys and `False` for regional KMS keys.
     *
     * For more information about multi-Region keys, see Multi-Region keys in KMS [^1] in the *Key Management Service
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/multi-region-keys-overview.html
     *
     * @var bool|null
     */
    private $multiRegion;

    /**
     * Lists the primary and replica keys in same multi-Region key. This field is present only when the value of the
     * `MultiRegion` field is `True`.
     *
     * For more information about any listed KMS key, use the DescribeKey operation.
     *
     * - `MultiRegionKeyType` indicates whether the KMS key is a `PRIMARY` or `REPLICA` key.
     * - `PrimaryKey` displays the key ARN and Region of the primary key. This field displays the current KMS key if it is
     *   the primary key.
     * - `ReplicaKeys` displays the key ARNs and Regions of all replica keys. This field includes the current KMS key if it
     *   is a replica key.
     *
     * @var MultiRegionConfiguration|null
     */
    private $multiRegionConfiguration;

    /**
     * The waiting period before the primary key in a multi-Region key is deleted. This waiting period begins when the last
     * of its replica keys is deleted. This value is present only when the `KeyState` of the KMS key is
     * `PendingReplicaDeletion`. That indicates that the KMS key is the primary key in a multi-Region key, it is scheduled
     * for deletion, and it still has existing replica keys.
     *
     * When a single-Region KMS key or a multi-Region replica key is scheduled for deletion, its deletion date is displayed
     * in the `DeletionDate` field. However, when the primary key in a multi-Region key is scheduled for deletion, its
     * waiting period doesn't begin until all of its replica keys are deleted. This value displays that waiting period. When
     * the last replica key in the multi-Region key is deleted, the `KeyState` of the scheduled primary key changes from
     * `PendingReplicaDeletion` to `PendingDeletion` and the deletion date appears in the `DeletionDate` field.
     *
     * @var int|null
     */
    private $pendingDeletionWindowInDays;

    /**
     * The message authentication code (MAC) algorithm that the HMAC KMS key supports.
     *
     * This value is present only when the `KeyUsage` of the KMS key is `GENERATE_VERIFY_MAC`.
     *
     * @var list<MacAlgorithmSpec::*>|null
     */
    private $macAlgorithms;

    /**
     * Information about the external key that is associated with a KMS key in an external key store.
     *
     * For more information, see External key [^1] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html#concept-external-key
     *
     * @var XksKeyConfigurationType|null
     */
    private $xksKeyConfiguration;

    /**
     * Identifies the current key material. This value is present for symmetric encryption keys with `AWS_KMS` origin and
     * single-Region, symmetric encryption keys with `EXTERNAL` origin. These KMS keys support automatic or on-demand key
     * rotation and can have multiple key materials associated with them. KMS uses the current key material for both
     * encryption and decryption, and the non-current key material for decryption operations only.
     *
     * @var string|null
     */
    private $currentKeyMaterialId;

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
     *   EncryptionAlgorithms?: null|array<EncryptionAlgorithmSpec::*>,
     *   SigningAlgorithms?: null|array<SigningAlgorithmSpec::*>,
     *   KeyAgreementAlgorithms?: null|array<KeyAgreementAlgorithmSpec::*>,
     *   MultiRegion?: null|bool,
     *   MultiRegionConfiguration?: null|MultiRegionConfiguration|array,
     *   PendingDeletionWindowInDays?: null|int,
     *   MacAlgorithms?: null|array<MacAlgorithmSpec::*>,
     *   XksKeyConfiguration?: null|XksKeyConfigurationType|array,
     *   CurrentKeyMaterialId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->awsAccountId = $input['AWSAccountId'] ?? null;
        $this->keyId = $input['KeyId'] ?? $this->throwException(new InvalidArgument('Missing required field "KeyId".'));
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
        $this->keyAgreementAlgorithms = $input['KeyAgreementAlgorithms'] ?? null;
        $this->multiRegion = $input['MultiRegion'] ?? null;
        $this->multiRegionConfiguration = isset($input['MultiRegionConfiguration']) ? MultiRegionConfiguration::create($input['MultiRegionConfiguration']) : null;
        $this->pendingDeletionWindowInDays = $input['PendingDeletionWindowInDays'] ?? null;
        $this->macAlgorithms = $input['MacAlgorithms'] ?? null;
        $this->xksKeyConfiguration = isset($input['XksKeyConfiguration']) ? XksKeyConfigurationType::create($input['XksKeyConfiguration']) : null;
        $this->currentKeyMaterialId = $input['CurrentKeyMaterialId'] ?? null;
    }

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
     *   EncryptionAlgorithms?: null|array<EncryptionAlgorithmSpec::*>,
     *   SigningAlgorithms?: null|array<SigningAlgorithmSpec::*>,
     *   KeyAgreementAlgorithms?: null|array<KeyAgreementAlgorithmSpec::*>,
     *   MultiRegion?: null|bool,
     *   MultiRegionConfiguration?: null|MultiRegionConfiguration|array,
     *   PendingDeletionWindowInDays?: null|int,
     *   MacAlgorithms?: null|array<MacAlgorithmSpec::*>,
     *   XksKeyConfiguration?: null|XksKeyConfigurationType|array,
     *   CurrentKeyMaterialId?: null|string,
     * }|KeyMetadata $input
     */
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

    public function getCurrentKeyMaterialId(): ?string
    {
        return $this->currentKeyMaterialId;
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

    /**
     * @return list<KeyAgreementAlgorithmSpec::*>
     */
    public function getKeyAgreementAlgorithms(): array
    {
        return $this->keyAgreementAlgorithms ?? [];
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

    /**
     * @return list<MacAlgorithmSpec::*>
     */
    public function getMacAlgorithms(): array
    {
        return $this->macAlgorithms ?? [];
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

    public function getXksKeyConfiguration(): ?XksKeyConfigurationType
    {
        return $this->xksKeyConfiguration;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
