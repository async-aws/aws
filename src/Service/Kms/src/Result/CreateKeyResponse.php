<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\ExpirationModelType;
use AsyncAws\Kms\Enum\KeyAgreementAlgorithmSpec;
use AsyncAws\Kms\Enum\KeyManagerType;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyState;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\MacAlgorithmSpec;
use AsyncAws\Kms\Enum\MultiRegionKeyType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\ValueObject\KeyMetadata;
use AsyncAws\Kms\ValueObject\MultiRegionConfiguration;
use AsyncAws\Kms\ValueObject\MultiRegionKey;
use AsyncAws\Kms\ValueObject\XksKeyConfigurationType;

class CreateKeyResponse extends Result
{
    /**
     * Metadata associated with the KMS key.
     *
     * @var KeyMetadata|null
     */
    private $keyMetadata;

    public function getKeyMetadata(): ?KeyMetadata
    {
        $this->initialize();

        return $this->keyMetadata;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->keyMetadata = empty($data['KeyMetadata']) ? null : $this->populateResultKeyMetadata($data['KeyMetadata']);
    }

    /**
     * @return list<EncryptionAlgorithmSpec::*>
     */
    private function populateResultEncryptionAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!EncryptionAlgorithmSpec::exists($a)) {
                    $a = EncryptionAlgorithmSpec::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return list<KeyAgreementAlgorithmSpec::*>
     */
    private function populateResultKeyAgreementAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!KeyAgreementAlgorithmSpec::exists($a)) {
                    $a = KeyAgreementAlgorithmSpec::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultKeyMetadata(array $json): KeyMetadata
    {
        return new KeyMetadata([
            'AWSAccountId' => isset($json['AWSAccountId']) ? (string) $json['AWSAccountId'] : null,
            'KeyId' => (string) $json['KeyId'],
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
            'CreationDate' => (isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDate'])))) ? $d : null,
            'Enabled' => isset($json['Enabled']) ? filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'KeyUsage' => isset($json['KeyUsage']) ? (!KeyUsageType::exists((string) $json['KeyUsage']) ? KeyUsageType::UNKNOWN_TO_SDK : (string) $json['KeyUsage']) : null,
            'KeyState' => isset($json['KeyState']) ? (!KeyState::exists((string) $json['KeyState']) ? KeyState::UNKNOWN_TO_SDK : (string) $json['KeyState']) : null,
            'DeletionDate' => (isset($json['DeletionDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['DeletionDate'])))) ? $d : null,
            'ValidTo' => (isset($json['ValidTo']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['ValidTo'])))) ? $d : null,
            'Origin' => isset($json['Origin']) ? (!OriginType::exists((string) $json['Origin']) ? OriginType::UNKNOWN_TO_SDK : (string) $json['Origin']) : null,
            'CustomKeyStoreId' => isset($json['CustomKeyStoreId']) ? (string) $json['CustomKeyStoreId'] : null,
            'CloudHsmClusterId' => isset($json['CloudHsmClusterId']) ? (string) $json['CloudHsmClusterId'] : null,
            'ExpirationModel' => isset($json['ExpirationModel']) ? (!ExpirationModelType::exists((string) $json['ExpirationModel']) ? ExpirationModelType::UNKNOWN_TO_SDK : (string) $json['ExpirationModel']) : null,
            'KeyManager' => isset($json['KeyManager']) ? (!KeyManagerType::exists((string) $json['KeyManager']) ? KeyManagerType::UNKNOWN_TO_SDK : (string) $json['KeyManager']) : null,
            'CustomerMasterKeySpec' => isset($json['CustomerMasterKeySpec']) ? (!CustomerMasterKeySpec::exists((string) $json['CustomerMasterKeySpec']) ? CustomerMasterKeySpec::UNKNOWN_TO_SDK : (string) $json['CustomerMasterKeySpec']) : null,
            'KeySpec' => isset($json['KeySpec']) ? (!KeySpec::exists((string) $json['KeySpec']) ? KeySpec::UNKNOWN_TO_SDK : (string) $json['KeySpec']) : null,
            'EncryptionAlgorithms' => !isset($json['EncryptionAlgorithms']) ? null : $this->populateResultEncryptionAlgorithmSpecList($json['EncryptionAlgorithms']),
            'SigningAlgorithms' => !isset($json['SigningAlgorithms']) ? null : $this->populateResultSigningAlgorithmSpecList($json['SigningAlgorithms']),
            'KeyAgreementAlgorithms' => !isset($json['KeyAgreementAlgorithms']) ? null : $this->populateResultKeyAgreementAlgorithmSpecList($json['KeyAgreementAlgorithms']),
            'MultiRegion' => isset($json['MultiRegion']) ? filter_var($json['MultiRegion'], \FILTER_VALIDATE_BOOLEAN) : null,
            'MultiRegionConfiguration' => empty($json['MultiRegionConfiguration']) ? null : $this->populateResultMultiRegionConfiguration($json['MultiRegionConfiguration']),
            'PendingDeletionWindowInDays' => isset($json['PendingDeletionWindowInDays']) ? (int) $json['PendingDeletionWindowInDays'] : null,
            'MacAlgorithms' => !isset($json['MacAlgorithms']) ? null : $this->populateResultMacAlgorithmSpecList($json['MacAlgorithms']),
            'XksKeyConfiguration' => empty($json['XksKeyConfiguration']) ? null : $this->populateResultXksKeyConfigurationType($json['XksKeyConfiguration']),
            'CurrentKeyMaterialId' => isset($json['CurrentKeyMaterialId']) ? (string) $json['CurrentKeyMaterialId'] : null,
        ]);
    }

    /**
     * @return list<MacAlgorithmSpec::*>
     */
    private function populateResultMacAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!MacAlgorithmSpec::exists($a)) {
                    $a = MacAlgorithmSpec::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultMultiRegionConfiguration(array $json): MultiRegionConfiguration
    {
        return new MultiRegionConfiguration([
            'MultiRegionKeyType' => isset($json['MultiRegionKeyType']) ? (!MultiRegionKeyType::exists((string) $json['MultiRegionKeyType']) ? MultiRegionKeyType::UNKNOWN_TO_SDK : (string) $json['MultiRegionKeyType']) : null,
            'PrimaryKey' => empty($json['PrimaryKey']) ? null : $this->populateResultMultiRegionKey($json['PrimaryKey']),
            'ReplicaKeys' => !isset($json['ReplicaKeys']) ? null : $this->populateResultMultiRegionKeyList($json['ReplicaKeys']),
        ]);
    }

    private function populateResultMultiRegionKey(array $json): MultiRegionKey
    {
        return new MultiRegionKey([
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
            'Region' => isset($json['Region']) ? (string) $json['Region'] : null,
        ]);
    }

    /**
     * @return MultiRegionKey[]
     */
    private function populateResultMultiRegionKeyList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMultiRegionKey($item);
        }

        return $items;
    }

    /**
     * @return list<SigningAlgorithmSpec::*>
     */
    private function populateResultSigningAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!SigningAlgorithmSpec::exists($a)) {
                    $a = SigningAlgorithmSpec::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultXksKeyConfigurationType(array $json): XksKeyConfigurationType
    {
        return new XksKeyConfigurationType([
            'Id' => isset($json['Id']) ? (string) $json['Id'] : null,
        ]);
    }
}
