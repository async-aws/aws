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

        $this->keyMetadata = empty($data['KeyMetadata']) ? null : new KeyMetadata([
            'AWSAccountId' => isset($data['KeyMetadata']['AWSAccountId']) ? (string) $data['KeyMetadata']['AWSAccountId'] : null,
            'KeyId' => (string) $data['KeyMetadata']['KeyId'],
            'Arn' => isset($data['KeyMetadata']['Arn']) ? (string) $data['KeyMetadata']['Arn'] : null,
            'CreationDate' => (isset($data['KeyMetadata']['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['KeyMetadata']['CreationDate'])))) ? $d : null,
            'Enabled' => isset($data['KeyMetadata']['Enabled']) ? filter_var($data['KeyMetadata']['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Description' => isset($data['KeyMetadata']['Description']) ? (string) $data['KeyMetadata']['Description'] : null,
            'KeyUsage' => isset($data['KeyMetadata']['KeyUsage']) ? (string) $data['KeyMetadata']['KeyUsage'] : null,
            'KeyState' => isset($data['KeyMetadata']['KeyState']) ? (string) $data['KeyMetadata']['KeyState'] : null,
            'DeletionDate' => (isset($data['KeyMetadata']['DeletionDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['KeyMetadata']['DeletionDate'])))) ? $d : null,
            'ValidTo' => (isset($data['KeyMetadata']['ValidTo']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['KeyMetadata']['ValidTo'])))) ? $d : null,
            'Origin' => isset($data['KeyMetadata']['Origin']) ? (string) $data['KeyMetadata']['Origin'] : null,
            'CustomKeyStoreId' => isset($data['KeyMetadata']['CustomKeyStoreId']) ? (string) $data['KeyMetadata']['CustomKeyStoreId'] : null,
            'CloudHsmClusterId' => isset($data['KeyMetadata']['CloudHsmClusterId']) ? (string) $data['KeyMetadata']['CloudHsmClusterId'] : null,
            'ExpirationModel' => isset($data['KeyMetadata']['ExpirationModel']) ? (string) $data['KeyMetadata']['ExpirationModel'] : null,
            'KeyManager' => isset($data['KeyMetadata']['KeyManager']) ? (string) $data['KeyMetadata']['KeyManager'] : null,
            'CustomerMasterKeySpec' => isset($data['KeyMetadata']['CustomerMasterKeySpec']) ? (string) $data['KeyMetadata']['CustomerMasterKeySpec'] : null,
            'KeySpec' => isset($data['KeyMetadata']['KeySpec']) ? (string) $data['KeyMetadata']['KeySpec'] : null,
            'EncryptionAlgorithms' => !isset($data['KeyMetadata']['EncryptionAlgorithms']) ? null : $this->populateResultEncryptionAlgorithmSpecList($data['KeyMetadata']['EncryptionAlgorithms']),
            'SigningAlgorithms' => !isset($data['KeyMetadata']['SigningAlgorithms']) ? null : $this->populateResultSigningAlgorithmSpecList($data['KeyMetadata']['SigningAlgorithms']),
            'MultiRegion' => isset($data['KeyMetadata']['MultiRegion']) ? filter_var($data['KeyMetadata']['MultiRegion'], \FILTER_VALIDATE_BOOLEAN) : null,
            'MultiRegionConfiguration' => empty($data['KeyMetadata']['MultiRegionConfiguration']) ? null : new MultiRegionConfiguration([
                'MultiRegionKeyType' => isset($data['KeyMetadata']['MultiRegionConfiguration']['MultiRegionKeyType']) ? (string) $data['KeyMetadata']['MultiRegionConfiguration']['MultiRegionKeyType'] : null,
                'PrimaryKey' => empty($data['KeyMetadata']['MultiRegionConfiguration']['PrimaryKey']) ? null : new MultiRegionKey([
                    'Arn' => isset($data['KeyMetadata']['MultiRegionConfiguration']['PrimaryKey']['Arn']) ? (string) $data['KeyMetadata']['MultiRegionConfiguration']['PrimaryKey']['Arn'] : null,
                    'Region' => isset($data['KeyMetadata']['MultiRegionConfiguration']['PrimaryKey']['Region']) ? (string) $data['KeyMetadata']['MultiRegionConfiguration']['PrimaryKey']['Region'] : null,
                ]),
                'ReplicaKeys' => !isset($data['KeyMetadata']['MultiRegionConfiguration']['ReplicaKeys']) ? null : $this->populateResultMultiRegionKeyList($data['KeyMetadata']['MultiRegionConfiguration']['ReplicaKeys']),
            ]),
            'PendingDeletionWindowInDays' => isset($data['KeyMetadata']['PendingDeletionWindowInDays']) ? (int) $data['KeyMetadata']['PendingDeletionWindowInDays'] : null,
        ]);
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

    /**
     * @return MultiRegionKey[]
     */
    private function populateResultMultiRegionKeyList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new MultiRegionKey([
                'Arn' => isset($item['Arn']) ? (string) $item['Arn'] : null,
                'Region' => isset($item['Region']) ? (string) $item['Region'] : null,
            ]);
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
