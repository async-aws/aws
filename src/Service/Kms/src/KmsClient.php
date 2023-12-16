<?php

namespace AsyncAws\Kms;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\Exception\AlreadyExistsException;
use AsyncAws\Kms\Exception\CloudHsmClusterInvalidConfigurationException;
use AsyncAws\Kms\Exception\CustomKeyStoreInvalidStateException;
use AsyncAws\Kms\Exception\CustomKeyStoreNotFoundException;
use AsyncAws\Kms\Exception\DependencyTimeoutException;
use AsyncAws\Kms\Exception\DisabledException;
use AsyncAws\Kms\Exception\DryRunOperationException;
use AsyncAws\Kms\Exception\IncorrectKeyException;
use AsyncAws\Kms\Exception\InvalidAliasNameException;
use AsyncAws\Kms\Exception\InvalidArnException;
use AsyncAws\Kms\Exception\InvalidCiphertextException;
use AsyncAws\Kms\Exception\InvalidGrantTokenException;
use AsyncAws\Kms\Exception\InvalidKeyUsageException;
use AsyncAws\Kms\Exception\InvalidMarkerException;
use AsyncAws\Kms\Exception\KeyUnavailableException;
use AsyncAws\Kms\Exception\KMSInternalException;
use AsyncAws\Kms\Exception\KMSInvalidStateException;
use AsyncAws\Kms\Exception\LimitExceededException;
use AsyncAws\Kms\Exception\MalformedPolicyDocumentException;
use AsyncAws\Kms\Exception\NotFoundException;
use AsyncAws\Kms\Exception\TagException;
use AsyncAws\Kms\Exception\UnsupportedOperationException;
use AsyncAws\Kms\Exception\XksKeyAlreadyInUseException;
use AsyncAws\Kms\Exception\XksKeyInvalidConfigurationException;
use AsyncAws\Kms\Exception\XksKeyNotFoundException;
use AsyncAws\Kms\Input\CreateAliasRequest;
use AsyncAws\Kms\Input\CreateKeyRequest;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\Input\SignRequest;
use AsyncAws\Kms\Result\CreateKeyResponse;
use AsyncAws\Kms\Result\DecryptResponse;
use AsyncAws\Kms\Result\EncryptResponse;
use AsyncAws\Kms\Result\GenerateDataKeyResponse;
use AsyncAws\Kms\Result\ListAliasesResponse;
use AsyncAws\Kms\Result\SignResponse;
use AsyncAws\Kms\ValueObject\RecipientInfo;
use AsyncAws\Kms\ValueObject\Tag;

class KmsClient extends AbstractApi
{
    /**
     * Creates a friendly name for a KMS key.
     *
     * > Adding, deleting, or updating an alias can allow or deny permission to the KMS key. For details, see ABAC for KMS
     * > [^1] in the *Key Management Service Developer Guide*.
     *
     * You can use an alias to identify a KMS key in the KMS console, in the DescribeKey operation and in cryptographic
     * operations [^2], such as Encrypt and GenerateDataKey. You can also change the KMS key that's associated with the
     * alias (UpdateAlias) or delete the alias (DeleteAlias) at any time. These operations don't affect the underlying KMS
     * key.
     *
     * You can associate the alias with any customer managed key in the same Amazon Web Services Region. Each alias is
     * associated with only one KMS key at a time, but a KMS key can have multiple aliases. A valid KMS key is required. You
     * can't create an alias without a KMS key.
     *
     * The alias must be unique in the account and Region, but you can have aliases with the same name in different Regions.
     * For detailed information about aliases, see Using aliases [^3] in the *Key Management Service Developer Guide*.
     *
     * This operation does not return a response. To get the alias that you created, use the ListAliases operation.
     *
     * The KMS key that you use for this operation must be in a compatible key state. For details, see Key states of KMS
     * keys [^4] in the *Key Management Service Developer Guide*.
     *
     * **Cross-account use**: No. You cannot perform this operation on an alias in a different Amazon Web Services account.
     *
     * **Required permissions**
     *
     * - kms:CreateAlias [^5] on the alias (IAM policy).
     * - kms:CreateAlias [^6] on the KMS key (key policy).
     *
     * For details, see Controlling access to aliases [^7] in the *Key Management Service Developer Guide*.
     *
     * **Related operations:**
     *
     * - DeleteAlias
     * - ListAliases
     * - UpdateAlias
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^8].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/abac.html
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-alias.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^6]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^7]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-alias.html#alias-access
     * [^8]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_CreateAlias.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#createalias
     *
     * @param array{
     *   AliasName: string,
     *   TargetKeyId: string,
     *   '@region'?: string|null,
     * }|CreateAliasRequest $input
     *
     * @throws DependencyTimeoutException
     * @throws AlreadyExistsException
     * @throws NotFoundException
     * @throws InvalidAliasNameException
     * @throws KMSInternalException
     * @throws LimitExceededException
     * @throws KMSInvalidStateException
     */
    public function createAlias($input): Result
    {
        $input = CreateAliasRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateAlias', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'AlreadyExistsException' => AlreadyExistsException::class,
            'NotFoundException' => NotFoundException::class,
            'InvalidAliasNameException' => InvalidAliasNameException::class,
            'KMSInternalException' => KMSInternalException::class,
            'LimitExceededException' => LimitExceededException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Creates a unique customer managed KMS key [^1] in your Amazon Web Services account and Region. You can use a KMS key
     * in cryptographic operations, such as encryption and signing. Some Amazon Web Services services let you use KMS keys
     * that you create and manage to protect your service resources.
     *
     * A KMS key is a logical representation of a cryptographic key. In addition to the key material used in cryptographic
     * operations, a KMS key includes metadata, such as the key ID, key policy, creation date, description, and key state.
     * For details, see Managing keys [^2] in the *Key Management Service Developer Guide*
     *
     * Use the parameters of `CreateKey` to specify the type of KMS key, the source of its key material, its key policy,
     * description, tags, and other properties.
     *
     * > KMS has replaced the term *customer master key (CMK)* with *KMS key* and *KMS key*. The concept has not changed. To
     * > prevent breaking changes, KMS is keeping some variations of this term.
     *
     * To create different types of KMS keys, use the following guidance:
     *
     * - `Symmetric encryption KMS key`:
     *
     *   By default, `CreateKey` creates a symmetric encryption KMS key with key material that KMS generates. This is the
     *   basic and most widely used type of KMS key, and provides the best performance.
     *
     *   To create a symmetric encryption KMS key, you don't need to specify any parameters. The default value for
     *   `KeySpec`, `SYMMETRIC_DEFAULT`, the default value for `KeyUsage`, `ENCRYPT_DECRYPT`, and the default value for
     *   `Origin`, `AWS_KMS`, create a symmetric encryption KMS key with KMS key material.
     *
     *   If you need a key for basic encryption and decryption or you are creating a KMS key to protect your resources in an
     *   Amazon Web Services service, create a symmetric encryption KMS key. The key material in a symmetric encryption key
     *   never leaves KMS unencrypted. You can use a symmetric encryption KMS key to encrypt and decrypt data up to 4,096
     *   bytes, but they are typically used to generate data keys and data keys pairs. For details, see GenerateDataKey and
     *   GenerateDataKeyPair.
     *
     * - `Asymmetric KMS keys`:
     *
     *   To create an asymmetric KMS key, use the `KeySpec` parameter to specify the type of key material in the KMS key.
     *   Then, use the `KeyUsage` parameter to determine whether the KMS key will be used to encrypt and decrypt or sign and
     *   verify. You can't change these properties after the KMS key is created.
     *
     *   Asymmetric KMS keys contain an RSA key pair, Elliptic Curve (ECC) key pair, or an SM2 key pair (China Regions
     *   only). The private key in an asymmetric KMS key never leaves KMS unencrypted. However, you can use the GetPublicKey
     *   operation to download the public key so it can be used outside of KMS. KMS keys with RSA or SM2 key pairs can be
     *   used to encrypt or decrypt data or sign and verify messages (but not both). KMS keys with ECC key pairs can be used
     *   only to sign and verify messages. For information about asymmetric KMS keys, see Asymmetric KMS keys [^3] in the
     *   *Key Management Service Developer Guide*.
     *
     * - `HMAC KMS key`:
     *
     *   To create an HMAC KMS key, set the `KeySpec` parameter to a key spec value for HMAC KMS keys. Then set the
     *   `KeyUsage` parameter to `GENERATE_VERIFY_MAC`. You must set the key usage even though `GENERATE_VERIFY_MAC` is the
     *   only valid key usage value for HMAC KMS keys. You can't change these properties after the KMS key is created.
     *
     *   HMAC KMS keys are symmetric keys that never leave KMS unencrypted. You can use HMAC keys to generate (GenerateMac)
     *   and verify (VerifyMac) HMAC codes for messages up to 4096 bytes.
     *
     * - `Multi-Region primary keys`
     * - `Imported key material`:
     *
     *   To create a multi-Region *primary key* in the local Amazon Web Services Region, use the `MultiRegion` parameter
     *   with a value of `True`. To create a multi-Region *replica key*, that is, a KMS key with the same key ID and key
     *   material as a primary key, but in a different Amazon Web Services Region, use the ReplicateKey operation. To change
     *   a replica key to a primary key, and its primary key to a replica key, use the UpdatePrimaryRegion operation.
     *
     *   You can create multi-Region KMS keys for all supported KMS key types: symmetric encryption KMS keys, HMAC KMS keys,
     *   asymmetric encryption KMS keys, and asymmetric signing KMS keys. You can also create multi-Region keys with
     *   imported key material. However, you can't create multi-Region keys in a custom key store.
     *
     *   This operation supports *multi-Region keys*, an KMS feature that lets you create multiple interoperable KMS keys in
     *   different Amazon Web Services Regions. Because these KMS keys have the same key ID, key material, and other
     *   metadata, you can use them interchangeably to encrypt data in one Amazon Web Services Region and decrypt it in a
     *   different Amazon Web Services Region without re-encrypting the data or making a cross-Region call. For more
     *   information about multi-Region keys, see Multi-Region keys in KMS [^4] in the *Key Management Service Developer
     *   Guide*.
     *
     * - To import your own key material into a KMS key, begin by creating a KMS key with no key material. To do this, use
     *   the `Origin` parameter of `CreateKey` with a value of `EXTERNAL`. Next, use GetParametersForImport operation to get
     *   a public key and import token. Use the wrapping public key to encrypt your key material. Then, use
     *   ImportKeyMaterial with your import token to import the key material. For step-by-step instructions, see Importing
     *   Key Material [^5] in the **Key Management Service Developer Guide**.
     *
     *   You can import key material into KMS keys of all supported KMS key types: symmetric encryption KMS keys, HMAC KMS
     *   keys, asymmetric encryption KMS keys, and asymmetric signing KMS keys. You can also create multi-Region keys with
     *   imported key material. However, you can't import key material into a KMS key in a custom key store.
     *
     *   To create a multi-Region primary key with imported key material, use the `Origin` parameter of `CreateKey` with a
     *   value of `EXTERNAL` and the `MultiRegion` parameter with a value of `True`. To create replicas of the multi-Region
     *   primary key, use the ReplicateKey operation. For instructions, see Importing key material into multi-Region keys
     *   [^6]. For more information about multi-Region keys, see Multi-Region keys in KMS [^7] in the *Key Management
     *   Service Developer Guide*.
     *
     * - `Custom key store`:
     *
     *   A custom key store [^8] lets you protect your Amazon Web Services resources using keys in a backing key store that
     *   you own and manage. When you request a cryptographic operation with a KMS key in a custom key store, the operation
     *   is performed in the backing key store using its cryptographic keys.
     *
     *   KMS supports CloudHSM key stores [^9] backed by an CloudHSM cluster and external key stores [^10] backed by an
     *   external key manager outside of Amazon Web Services. When you create a KMS key in an CloudHSM key store, KMS
     *   generates an encryption key in the CloudHSM cluster and associates it with the KMS key. When you create a KMS key
     *   in an external key store, you specify an existing encryption key in the external key manager.
     *
     *   > Some external key managers provide a simpler method for creating a KMS key in an external key store. For details,
     *   > see your external key manager documentation.
     *
     *   Before you create a KMS key in a custom key store, the `ConnectionState` of the key store must be `CONNECTED`. To
     *   connect the custom key store, use the ConnectCustomKeyStore operation. To find the `ConnectionState`, use the
     *   DescribeCustomKeyStores operation.
     *
     *   To create a KMS key in a custom key store, use the `CustomKeyStoreId`. Use the default `KeySpec` value,
     *   `SYMMETRIC_DEFAULT`, and the default `KeyUsage` value, `ENCRYPT_DECRYPT` to create a symmetric encryption key. No
     *   other key type is supported in a custom key store.
     *
     *   To create a KMS key in an CloudHSM key store [^11], use the `Origin` parameter with a value of `AWS_CLOUDHSM`. The
     *   CloudHSM cluster that is associated with the custom key store must have at least two active HSMs in different
     *   Availability Zones in the Amazon Web Services Region.
     *
     *   To create a KMS key in an external key store [^12], use the `Origin` parameter with a value of `EXTERNAL_KEY_STORE`
     *   and an `XksKeyId` parameter that identifies an existing external key.
     *
     *   > Some external key managers provide a simpler method for creating a KMS key in an external key store. For details,
     *   > see your external key manager documentation.
     *
     *
     * **Cross-account use**: No. You cannot use this operation to create a KMS key in a different Amazon Web Services
     * account.
     *
     * **Required permissions**: kms:CreateKey [^13] (IAM policy). To use the `Tags` parameter, kms:TagResource [^14] (IAM
     * policy). For examples and information about related permissions, see Allow a user to create KMS keys [^15] in the
     * *Key Management Service Developer Guide*.
     *
     * **Related operations:**
     *
     * - DescribeKey
     * - ListKeys
     * - ScheduleKeyDeletion
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^16].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#kms-keys
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/getting-started.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/symmetric-asymmetric.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/multi-region-keys-overview.html
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/importing-keys.html
     * [^6]: https://docs.aws.amazon.com/kms/latest/developerguide/multi-region-keys-import.html
     * [^7]: https://docs.aws.amazon.com/kms/latest/developerguide/multi-region-keys-overview.html
     * [^8]: https://docs.aws.amazon.com/kms/latest/developerguide/custom-key-store-overview.html
     * [^9]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-cloudhsm.html
     * [^10]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html
     * [^11]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-cloudhsm.html
     * [^12]: https://docs.aws.amazon.com/kms/latest/developerguide/keystore-external.html
     * [^13]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^14]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^15]: https://docs.aws.amazon.com/kms/latest/developerguide/iam-policies.html#iam-policy-example-create-key
     * [^16]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_CreateKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#createkey
     *
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
     *
     * @throws MalformedPolicyDocumentException
     * @throws DependencyTimeoutException
     * @throws InvalidArnException
     * @throws UnsupportedOperationException
     * @throws KMSInternalException
     * @throws LimitExceededException
     * @throws TagException
     * @throws CustomKeyStoreNotFoundException
     * @throws CustomKeyStoreInvalidStateException
     * @throws CloudHsmClusterInvalidConfigurationException
     * @throws XksKeyInvalidConfigurationException
     * @throws XksKeyAlreadyInUseException
     * @throws XksKeyNotFoundException
     */
    public function createKey($input = []): CreateKeyResponse
    {
        $input = CreateKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'MalformedPolicyDocumentException' => MalformedPolicyDocumentException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidArnException' => InvalidArnException::class,
            'UnsupportedOperationException' => UnsupportedOperationException::class,
            'KMSInternalException' => KMSInternalException::class,
            'LimitExceededException' => LimitExceededException::class,
            'TagException' => TagException::class,
            'CustomKeyStoreNotFoundException' => CustomKeyStoreNotFoundException::class,
            'CustomKeyStoreInvalidStateException' => CustomKeyStoreInvalidStateException::class,
            'CloudHsmClusterInvalidConfigurationException' => CloudHsmClusterInvalidConfigurationException::class,
            'XksKeyInvalidConfigurationException' => XksKeyInvalidConfigurationException::class,
            'XksKeyAlreadyInUseException' => XksKeyAlreadyInUseException::class,
            'XksKeyNotFoundException' => XksKeyNotFoundException::class,
        ]]));

        return new CreateKeyResponse($response);
    }

    /**
     * Decrypts ciphertext that was encrypted by a KMS key using any of the following operations:.
     *
     * - Encrypt
     * - GenerateDataKey
     * - GenerateDataKeyPair
     * - GenerateDataKeyWithoutPlaintext
     * - GenerateDataKeyPairWithoutPlaintext
     *
     * You can use this operation to decrypt ciphertext that was encrypted under a symmetric encryption KMS key or an
     * asymmetric encryption KMS key. When the KMS key is asymmetric, you must specify the KMS key and the encryption
     * algorithm that was used to encrypt the ciphertext. For information about asymmetric KMS keys, see Asymmetric KMS keys
     * [^1] in the *Key Management Service Developer Guide*.
     *
     * The `Decrypt` operation also decrypts ciphertext that was encrypted outside of KMS by the public key in an KMS
     * asymmetric KMS key. However, it cannot decrypt symmetric ciphertext produced by other libraries, such as the Amazon
     * Web Services Encryption SDK [^2] or Amazon S3 client-side encryption [^3]. These libraries return a ciphertext format
     * that is incompatible with KMS.
     *
     * If the ciphertext was encrypted under a symmetric encryption KMS key, the `KeyId` parameter is optional. KMS can get
     * this information from metadata that it adds to the symmetric ciphertext blob. This feature adds durability to your
     * implementation by ensuring that authorized users can decrypt ciphertext decades after it was encrypted, even if
     * they've lost track of the key ID. However, specifying the KMS key is always recommended as a best practice. When you
     * use the `KeyId` parameter to specify a KMS key, KMS only uses the KMS key you specify. If the ciphertext was
     * encrypted under a different KMS key, the `Decrypt` operation fails. This practice ensures that you use the KMS key
     * that you intend.
     *
     * Whenever possible, use key policies to give users permission to call the `Decrypt` operation on a particular KMS key,
     * instead of using &IAM; policies. Otherwise, you might create an &IAM; policy that gives the user `Decrypt`
     * permission on all KMS keys. This user could decrypt ciphertext that was encrypted by KMS keys in other accounts if
     * the key policy for the cross-account KMS key permits it. If you must use an IAM policy for `Decrypt` permissions,
     * limit the user to particular KMS keys or particular trusted accounts. For details, see Best practices for IAM
     * policies [^4] in the *Key Management Service Developer Guide*.
     *
     * `Decrypt` also supports Amazon Web Services Nitro Enclaves [^5], which provide an isolated compute environment in
     * Amazon EC2. To call `Decrypt` for a Nitro enclave, use the Amazon Web Services Nitro Enclaves SDK [^6] or any Amazon
     * Web Services SDK. Use the `Recipient` parameter to provide the attestation document for the enclave. Instead of the
     * plaintext data, the response includes the plaintext data encrypted with the public key from the attestation document
     * (`CiphertextForRecipient`). For information about the interaction between KMS and Amazon Web Services Nitro Enclaves,
     * see How Amazon Web Services Nitro Enclaves uses KMS [^7] in the *Key Management Service Developer Guide*.
     *
     * The KMS key that you use for this operation must be in a compatible key state. For details, see Key states of KMS
     * keys [^8] in the *Key Management Service Developer Guide*.
     *
     * **Cross-account use**: Yes. If you use the `KeyId` parameter to identify a KMS key in a different Amazon Web Services
     * account, specify the key ARN or the alias ARN of the KMS key.
     *
     * **Required permissions**: kms:Decrypt [^9] (key policy)
     *
     * **Related operations:**
     *
     * - Encrypt
     * - GenerateDataKey
     * - GenerateDataKeyPair
     * - ReEncrypt
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^10].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/symmetric-asymmetric.html
     * [^2]: https://docs.aws.amazon.com/encryption-sdk/latest/developer-guide/
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingClientSideEncryption.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/iam-policies.html#iam-policies-best-practices
     * [^5]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave.html
     * [^6]: https://docs.aws.amazon.com/enclaves/latest/user/developing-applications.html#sdk
     * [^7]: https://docs.aws.amazon.com/kms/latest/developerguide/services-nitro-enclaves.html
     * [^8]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     * [^9]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^10]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_Decrypt.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#decrypt
     *
     * @param array{
     *   CiphertextBlob: string,
     *   EncryptionContext?: null|array<string, string>,
     *   GrantTokens?: null|string[],
     *   KeyId?: null|string,
     *   EncryptionAlgorithm?: null|EncryptionAlgorithmSpec::*,
     *   Recipient?: null|RecipientInfo|array,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|DecryptRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws InvalidCiphertextException
     * @throws KeyUnavailableException
     * @throws IncorrectKeyException
     * @throws InvalidKeyUsageException
     * @throws DependencyTimeoutException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     * @throws DryRunOperationException
     */
    public function decrypt($input): DecryptResponse
    {
        $input = DecryptRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Decrypt', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'InvalidCiphertextException' => InvalidCiphertextException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'IncorrectKeyException' => IncorrectKeyException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'DryRunOperationException' => DryRunOperationException::class,
        ]]));

        return new DecryptResponse($response);
    }

    /**
     * Encrypts plaintext of up to 4,096 bytes using a KMS key. You can use a symmetric or asymmetric KMS key with a
     * `KeyUsage` of `ENCRYPT_DECRYPT`.
     *
     * You can use this operation to encrypt small amounts of arbitrary data, such as a personal identifier or database
     * password, or other sensitive information. You don't need to use the `Encrypt` operation to encrypt a data key. The
     * GenerateDataKey and GenerateDataKeyPair operations return a plaintext data key and an encrypted copy of that data
     * key.
     *
     * If you use a symmetric encryption KMS key, you can use an encryption context to add additional security to your
     * encryption operation. If you specify an `EncryptionContext` when encrypting data, you must specify the same
     * encryption context (a case-sensitive exact match) when decrypting the data. Otherwise, the request to decrypt fails
     * with an `InvalidCiphertextException`. For more information, see Encryption Context [^1] in the *Key Management
     * Service Developer Guide*.
     *
     * If you specify an asymmetric KMS key, you must also specify the encryption algorithm. The algorithm must be
     * compatible with the KMS key spec.
     *
     * ! When you use an asymmetric KMS key to encrypt or reencrypt data, be sure to record the KMS key and encryption
     * ! algorithm that you choose. You will be required to provide the same KMS key and encryption algorithm when you
     * ! decrypt the data. If the KMS key and algorithm do not match the values used to encrypt the data, the decrypt
     * ! operation fails.
     * !
     * ! You are not required to supply the key ID and encryption algorithm when you decrypt with symmetric encryption KMS
     * ! keys because KMS stores this information in the ciphertext blob. KMS cannot store metadata in ciphertext generated
     * ! with asymmetric keys. The standard format for asymmetric key ciphertext does not include configurable fields.
     *
     * The maximum size of the data that you can encrypt varies with the type of KMS key and the encryption algorithm that
     * you choose.
     *
     * - Symmetric encryption KMS keys
     *
     *   - `SYMMETRIC_DEFAULT`: 4096 bytes
     *
     * - `RSA_2048`
     *
     *   - `RSAES_OAEP_SHA_1`: 214 bytes
     *   - `RSAES_OAEP_SHA_256`: 190 bytes
     *
     * - `RSA_3072`
     *
     *   - `RSAES_OAEP_SHA_1`: 342 bytes
     *   - `RSAES_OAEP_SHA_256`: 318 bytes
     *
     * - `RSA_4096`
     *
     *   - `RSAES_OAEP_SHA_1`: 470 bytes
     *   - `RSAES_OAEP_SHA_256`: 446 bytes
     *
     * - `SM2PKE`: 1024 bytes (China Regions only)
     *
     * The KMS key that you use for this operation must be in a compatible key state. For details, see Key states of KMS
     * keys [^2] in the *Key Management Service Developer Guide*.
     *
     * **Cross-account use**: Yes. To perform this operation with a KMS key in a different Amazon Web Services account,
     * specify the key ARN or alias ARN in the value of the `KeyId` parameter.
     *
     * **Required permissions**: kms:Encrypt [^3] (key policy)
     *
     * **Related operations:**
     *
     * - Decrypt
     * - GenerateDataKey
     * - GenerateDataKeyPair
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^4].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#encrypt_context
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_Encrypt.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#encrypt
     *
     * @param array{
     *   KeyId: string,
     *   Plaintext: string,
     *   EncryptionContext?: null|array<string, string>,
     *   GrantTokens?: null|string[],
     *   EncryptionAlgorithm?: null|EncryptionAlgorithmSpec::*,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|EncryptRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws KeyUnavailableException
     * @throws DependencyTimeoutException
     * @throws InvalidKeyUsageException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     * @throws DryRunOperationException
     */
    public function encrypt($input): EncryptResponse
    {
        $input = EncryptRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Encrypt', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'DryRunOperationException' => DryRunOperationException::class,
        ]]));

        return new EncryptResponse($response);
    }

    /**
     * Returns a unique symmetric data key for use outside of KMS. This operation returns a plaintext copy of the data key
     * and a copy that is encrypted under a symmetric encryption KMS key that you specify. The bytes in the plaintext key
     * are random; they are not related to the caller or the KMS key. You can use the plaintext key to encrypt your data
     * outside of KMS and store the encrypted data key with the encrypted data.
     *
     * To generate a data key, specify the symmetric encryption KMS key that will be used to encrypt the data key. You
     * cannot use an asymmetric KMS key to encrypt data keys. To get the type of your KMS key, use the DescribeKey
     * operation.
     *
     * You must also specify the length of the data key. Use either the `KeySpec` or `NumberOfBytes` parameters (but not
     * both). For 128-bit and 256-bit data keys, use the `KeySpec` parameter.
     *
     * To generate a 128-bit SM4 data key (China Regions only), specify a `KeySpec` value of `AES_128` or a `NumberOfBytes`
     * value of `16`. The symmetric encryption key used in China Regions to encrypt your data key is an SM4 encryption key.
     *
     * To get only an encrypted copy of the data key, use GenerateDataKeyWithoutPlaintext. To generate an asymmetric data
     * key pair, use the GenerateDataKeyPair or GenerateDataKeyPairWithoutPlaintext operation. To get a cryptographically
     * secure random byte string, use GenerateRandom.
     *
     * You can use an optional encryption context to add additional security to the encryption operation. If you specify an
     * `EncryptionContext`, you must specify the same encryption context (a case-sensitive exact match) when decrypting the
     * encrypted data key. Otherwise, the request to decrypt fails with an `InvalidCiphertextException`. For more
     * information, see Encryption Context [^1] in the *Key Management Service Developer Guide*.
     *
     * `GenerateDataKey` also supports Amazon Web Services Nitro Enclaves [^2], which provide an isolated compute
     * environment in Amazon EC2. To call `GenerateDataKey` for an Amazon Web Services Nitro enclave, use the Amazon Web
     * Services Nitro Enclaves SDK [^3] or any Amazon Web Services SDK. Use the `Recipient` parameter to provide the
     * attestation document for the enclave. `GenerateDataKey` returns a copy of the data key encrypted under the specified
     * KMS key, as usual. But instead of a plaintext copy of the data key, the response includes a copy of the data key
     * encrypted under the public key from the attestation document (`CiphertextForRecipient`). For information about the
     * interaction between KMS and Amazon Web Services Nitro Enclaves, see How Amazon Web Services Nitro Enclaves uses KMS
     * [^4] in the *Key Management Service Developer Guide*..
     *
     * The KMS key that you use for this operation must be in a compatible key state. For details, see Key states of KMS
     * keys [^5] in the *Key Management Service Developer Guide*.
     *
     * **How to use your data key**
     *
     * We recommend that you use the following pattern to encrypt data locally in your application. You can write your own
     * code or use a client-side encryption library, such as the Amazon Web Services Encryption SDK [^6], the Amazon
     * DynamoDB Encryption Client [^7], or Amazon S3 client-side encryption [^8] to do these tasks for you.
     *
     * To encrypt data outside of KMS:
     *
     * 1. Use the `GenerateDataKey` operation to get a data key.
     * 2. Use the plaintext data key (in the `Plaintext` field of the response) to encrypt your data outside of KMS. Then
     *    erase the plaintext data key from memory.
     * 3. Store the encrypted data key (in the `CiphertextBlob` field of the response) with the encrypted data.
     *
     * To decrypt data outside of KMS:
     *
     * - Use the Decrypt operation to decrypt the encrypted data key. The operation returns a plaintext copy of the data
     *   key.
     * - Use the plaintext data key to decrypt data outside of KMS, then erase the plaintext data key from memory.
     *
     * **Cross-account use**: Yes. To perform this operation with a KMS key in a different Amazon Web Services account,
     * specify the key ARN or alias ARN in the value of the `KeyId` parameter.
     *
     * **Required permissions**: kms:GenerateDataKey [^9] (key policy)
     *
     * **Related operations:**
     *
     * - Decrypt
     * - Encrypt
     * - GenerateDataKeyPair
     * - GenerateDataKeyPairWithoutPlaintext
     * - GenerateDataKeyWithoutPlaintext
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^10].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#encrypt_context
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave.html
     * [^3]: https://docs.aws.amazon.com/enclaves/latest/user/developing-applications.html#sdk
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/services-nitro-enclaves.html
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     * [^6]: https://docs.aws.amazon.com/encryption-sdk/latest/developer-guide/
     * [^7]: https://docs.aws.amazon.com/dynamodb-encryption-client/latest/devguide/
     * [^8]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingClientSideEncryption.html
     * [^9]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^10]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_GenerateDataKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#generatedatakey
     *
     * @param array{
     *   KeyId: string,
     *   EncryptionContext?: null|array<string, string>,
     *   NumberOfBytes?: null|int,
     *   KeySpec?: null|DataKeySpec::*,
     *   GrantTokens?: null|string[],
     *   Recipient?: null|RecipientInfo|array,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|GenerateDataKeyRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws KeyUnavailableException
     * @throws DependencyTimeoutException
     * @throws InvalidKeyUsageException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     * @throws DryRunOperationException
     */
    public function generateDataKey($input): GenerateDataKeyResponse
    {
        $input = GenerateDataKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GenerateDataKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'DryRunOperationException' => DryRunOperationException::class,
        ]]));

        return new GenerateDataKeyResponse($response);
    }

    /**
     * Gets a list of aliases in the caller's Amazon Web Services account and region. For more information about aliases,
     * see CreateAlias.
     *
     * By default, the `ListAliases` operation returns all aliases in the account and region. To get only the aliases
     * associated with a particular KMS key, use the `KeyId` parameter.
     *
     * The `ListAliases` response can include aliases that you created and associated with your customer managed keys, and
     * aliases that Amazon Web Services created and associated with Amazon Web Services managed keys in your account. You
     * can recognize Amazon Web Services aliases because their names have the format `aws/<service-name>`, such as
     * `aws/dynamodb`.
     *
     * The response might also include aliases that have no `TargetKeyId` field. These are predefined aliases that Amazon
     * Web Services has created but has not yet associated with a KMS key. Aliases that Amazon Web Services creates in your
     * account, including predefined aliases, do not count against your KMS aliases quota [^1].
     *
     * **Cross-account use**: No. `ListAliases` does not return aliases in other Amazon Web Services accounts.
     *
     * **Required permissions**: kms:ListAliases [^2] (IAM policy)
     *
     * For details, see Controlling access to aliases [^3] in the *Key Management Service Developer Guide*.
     *
     * **Related operations:**
     *
     * - CreateAlias
     * - DeleteAlias
     * - UpdateAlias
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^4].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/limits.html#aliases-limit
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-alias.html#alias-access
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_ListAliases.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#listaliases
     *
     * @param array{
     *   KeyId?: null|string,
     *   Limit?: null|int,
     *   Marker?: null|string,
     *   '@region'?: string|null,
     * }|ListAliasesRequest $input
     *
     * @throws DependencyTimeoutException
     * @throws InvalidMarkerException
     * @throws KMSInternalException
     * @throws InvalidArnException
     * @throws NotFoundException
     */
    public function listAliases($input = []): ListAliasesResponse
    {
        $input = ListAliasesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListAliases', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidMarkerException' => InvalidMarkerException::class,
            'KMSInternalException' => KMSInternalException::class,
            'InvalidArnException' => InvalidArnException::class,
            'NotFoundException' => NotFoundException::class,
        ]]));

        return new ListAliasesResponse($response, $this, $input);
    }

    /**
     * Creates a digital signature [^1] for a message or message digest by using the private key in an asymmetric signing
     * KMS key. To verify the signature, use the Verify operation, or use the public key in the same asymmetric KMS key
     * outside of KMS. For information about asymmetric KMS keys, see Asymmetric KMS keys [^2] in the *Key Management
     * Service Developer Guide*.
     *
     * Digital signatures are generated and verified by using asymmetric key pair, such as an RSA or ECC pair that is
     * represented by an asymmetric KMS key. The key owner (or an authorized user) uses their private key to sign a message.
     * Anyone with the public key can verify that the message was signed with that particular private key and that the
     * message hasn't changed since it was signed.
     *
     * To use the `Sign` operation, provide the following information:
     *
     * - Use the `KeyId` parameter to identify an asymmetric KMS key with a `KeyUsage` value of `SIGN_VERIFY`. To get the
     *   `KeyUsage` value of a KMS key, use the DescribeKey operation. The caller must have `kms:Sign` permission on the KMS
     *   key.
     * - Use the `Message` parameter to specify the message or message digest to sign. You can submit messages of up to 4096
     *   bytes. To sign a larger message, generate a hash digest of the message, and then provide the hash digest in the
     *   `Message` parameter. To indicate whether the message is a full message or a digest, use the `MessageType`
     *   parameter.
     * - Choose a signing algorithm that is compatible with the KMS key.
     *
     * ! When signing a message, be sure to record the KMS key and the signing algorithm. This information is required to
     * ! verify the signature.
     *
     * > Best practices recommend that you limit the time during which any signature is effective. This deters an attack
     * > where the actor uses a signed message to establish validity repeatedly or long after the message is superseded.
     * > Signatures do not include a timestamp, but you can include a timestamp in the signed message to help you detect
     * > when its time to refresh the signature.
     *
     * To verify the signature that this operation generates, use the Verify operation. Or use the GetPublicKey operation to
     * download the public key and then use the public key to verify the signature outside of KMS.
     *
     * The KMS key that you use for this operation must be in a compatible key state. For details, see Key states of KMS
     * keys [^3] in the *Key Management Service Developer Guide*.
     *
     * **Cross-account use**: Yes. To perform this operation with a KMS key in a different Amazon Web Services account,
     * specify the key ARN or alias ARN in the value of the `KeyId` parameter.
     *
     * **Required permissions**: kms:Sign [^4] (key policy)
     *
     * **Related operations**: Verify
     *
     * **Eventual consistency**: The KMS API follows an eventual consistency model. For more information, see KMS eventual
     * consistency [^5].
     *
     * [^1]: https://en.wikipedia.org/wiki/Digital_signature
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/symmetric-asymmetric.html
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-api-permissions-reference.html
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/programming-eventual-consistency.html
     *
     * @see https://docs.aws.amazon.com/kms/latest/APIReference/API_Sign.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kms-2014-11-01.html#sign
     *
     * @param array{
     *   KeyId: string,
     *   Message: string,
     *   MessageType?: null|MessageType::*,
     *   GrantTokens?: null|string[],
     *   SigningAlgorithm: SigningAlgorithmSpec::*,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|SignRequest $input
     *
     * @throws NotFoundException
     * @throws DisabledException
     * @throws KeyUnavailableException
     * @throws DependencyTimeoutException
     * @throws InvalidKeyUsageException
     * @throws InvalidGrantTokenException
     * @throws KMSInternalException
     * @throws KMSInvalidStateException
     * @throws DryRunOperationException
     */
    public function sign($input): SignResponse
    {
        $input = SignRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Sign', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NotFoundException' => NotFoundException::class,
            'DisabledException' => DisabledException::class,
            'KeyUnavailableException' => KeyUnavailableException::class,
            'DependencyTimeoutException' => DependencyTimeoutException::class,
            'InvalidKeyUsageException' => InvalidKeyUsageException::class,
            'InvalidGrantTokenException' => InvalidGrantTokenException::class,
            'KMSInternalException' => KMSInternalException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'DryRunOperationException' => DryRunOperationException::class,
        ]]));

        return new SignResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-northeast-3':
            case 'ap-south-1':
            case 'ap-south-2':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ap-southeast-3':
            case 'ap-southeast-4':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-central-2':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'il-central-1':
            case 'me-central-1':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-east-1':
            case 'us-gov-west-1':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://kms.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ProdFips':
            case 'us-isob-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://kms.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'af-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.af-south-1.amazonaws.com',
                    'signRegion' => 'af-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-east-1.amazonaws.com',
                    'signRegion' => 'ap-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-1.amazonaws.com',
                    'signRegion' => 'ap-northeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-2.amazonaws.com',
                    'signRegion' => 'ap-northeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-northeast-3.amazonaws.com',
                    'signRegion' => 'ap-northeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-south-1.amazonaws.com',
                    'signRegion' => 'ap-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-south-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-south-2.amazonaws.com',
                    'signRegion' => 'ap-south-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-1.amazonaws.com',
                    'signRegion' => 'ap-southeast-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-2.amazonaws.com',
                    'signRegion' => 'ap-southeast-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-3.amazonaws.com',
                    'signRegion' => 'ap-southeast-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-4-fips':
                return [
                    'endpoint' => 'https://kms-fips.ap-southeast-4.amazonaws.com',
                    'signRegion' => 'ap-southeast-4',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-central-1.amazonaws.com',
                    'signRegion' => 'eu-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-central-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-central-2.amazonaws.com',
                    'signRegion' => 'eu-central-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-north-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-north-1.amazonaws.com',
                    'signRegion' => 'eu-north-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-south-1.amazonaws.com',
                    'signRegion' => 'eu-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-south-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-south-2.amazonaws.com',
                    'signRegion' => 'eu-south-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-1.amazonaws.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-2.amazonaws.com',
                    'signRegion' => 'eu-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-3-fips':
                return [
                    'endpoint' => 'https://kms-fips.eu-west-3.amazonaws.com',
                    'signRegion' => 'eu-west-3',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'il-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.il-central-1.amazonaws.com',
                    'signRegion' => 'il-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'me-central-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.me-central-1.amazonaws.com',
                    'signRegion' => 'me-central-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'me-south-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.me-south-1.amazonaws.com',
                    'signRegion' => 'me-south-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'sa-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.sa-east-1.amazonaws.com',
                    'signRegion' => 'sa-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://kms.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://kms.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-west-1-fips':
                return [
                    'endpoint' => 'https://kms-fips.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 'kms',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Kms".', $region));
    }
}
