<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\ValueObject\KeyMetadata;
use AsyncAws\Kms\ValueObject\MultiRegionConfiguration;
use AsyncAws\Kms\ValueObject\MultiRegionKey;

class CreateKeyResponse extends Result
{
    /**
     * Metadata associated with the KMS key.
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
            'CreationDate' => (isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['CreationDate'])))) ? $d : null,
            'Enabled' => isset($json['Enabled']) ? filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'KeyUsage' => isset($json['KeyUsage']) ? (string) $json['KeyUsage'] : null,
            'KeyState' => isset($json['KeyState']) ? (string) $json['KeyState'] : null,
            'DeletionDate' => (isset($json['DeletionDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['DeletionDate'])))) ? $d : null,
            'ValidTo' => (isset($json['ValidTo']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['ValidTo'])))) ? $d : null,
            'Origin' => isset($json['Origin']) ? (string) $json['Origin'] : null,
            'CustomKeyStoreId' => isset($json['CustomKeyStoreId']) ? (string) $json['CustomKeyStoreId'] : null,
            'CloudHsmClusterId' => isset($json['CloudHsmClusterId']) ? (string) $json['CloudHsmClusterId'] : null,
            'ExpirationModel' => isset($json['ExpirationModel']) ? (string) $json['ExpirationModel'] : null,
            'KeyManager' => isset($json['KeyManager']) ? (string) $json['KeyManager'] : null,
            'CustomerMasterKeySpec' => isset($json['CustomerMasterKeySpec']) ? (string) $json['CustomerMasterKeySpec'] : null,
            'KeySpec' => isset($json['KeySpec']) ? (string) $json['KeySpec'] : null,
            'EncryptionAlgorithms' => !isset($json['EncryptionAlgorithms']) ? null : $this->populateResultEncryptionAlgorithmSpecList($json['EncryptionAlgorithms']),
            'SigningAlgorithms' => !isset($json['SigningAlgorithms']) ? null : $this->populateResultSigningAlgorithmSpecList($json['SigningAlgorithms']),
            'MultiRegion' => isset($json['MultiRegion']) ? filter_var($json['MultiRegion'], \FILTER_VALIDATE_BOOLEAN) : null,
            'MultiRegionConfiguration' => empty($json['MultiRegionConfiguration']) ? null : $this->populateResultMultiRegionConfiguration($json['MultiRegionConfiguration']),
            'PendingDeletionWindowInDays' => isset($json['PendingDeletionWindowInDays']) ? (int) $json['PendingDeletionWindowInDays'] : null,
        ]);
    }

    private function populateResultMultiRegionConfiguration(array $json): MultiRegionConfiguration
    {
        return new MultiRegionConfiguration([
            'MultiRegionKeyType' => isset($json['MultiRegionKeyType']) ? (string) $json['MultiRegionKeyType'] : null,
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
                $items[] = $a;
            }
        }

        return $items;
    }
}
