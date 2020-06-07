<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\ArchivalSummary;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\BillingModeSummary;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\Projection;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputDescription;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputOverride;
use AsyncAws\DynamoDb\ValueObject\ReplicaDescription;
use AsyncAws\DynamoDb\ValueObject\ReplicaGlobalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\RestoreSummary;
use AsyncAws\DynamoDb\ValueObject\SSEDescription;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\TableDescription;

class CreateTableOutput extends Result
{
    /**
     * Represents the properties of the table.
     */
    private $TableDescription;

    public function getTableDescription(): ?TableDescription
    {
        $this->initialize();

        return $this->TableDescription;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $fn = [];
        $fn['list-AttributeDefinitions'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new AttributeDefinition([
                    'AttributeName' => (string) $item['AttributeName'],
                    'AttributeType' => (string) $item['AttributeType'],
                ]);
            }

            return $items;
        };
        $fn['list-KeySchema'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new KeySchemaElement([
                    'AttributeName' => (string) $item['AttributeName'],
                    'KeyType' => (string) $item['KeyType'],
                ]);
            }

            return $items;
        };
        $fn['list-LocalSecondaryIndexDescriptionList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new LocalSecondaryIndexDescription([
                    'IndexName' => isset($item['IndexName']) ? (string) $item['IndexName'] : null,
                    'KeySchema' => empty($item['KeySchema']) ? [] : $fn['list-KeySchema']($item['KeySchema']),
                    'Projection' => empty($item['Projection']) ? null : new Projection([
                        'ProjectionType' => isset($item['Projection']['ProjectionType']) ? (string) $item['Projection']['ProjectionType'] : null,
                        'NonKeyAttributes' => empty($item['Projection']['NonKeyAttributes']) ? [] : $fn['list-NonKeyAttributeNameList']($item['Projection']['NonKeyAttributes']),
                    ]),
                    'IndexSizeBytes' => isset($item['IndexSizeBytes']) ? (string) $item['IndexSizeBytes'] : null,
                    'ItemCount' => isset($item['ItemCount']) ? (string) $item['ItemCount'] : null,
                    'IndexArn' => isset($item['IndexArn']) ? (string) $item['IndexArn'] : null,
                ]);
            }

            return $items;
        };
        $fn['list-NonKeyAttributeNameList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (string) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        };
        $fn['list-GlobalSecondaryIndexDescriptionList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new GlobalSecondaryIndexDescription([
                    'IndexName' => isset($item['IndexName']) ? (string) $item['IndexName'] : null,
                    'KeySchema' => empty($item['KeySchema']) ? [] : $fn['list-KeySchema']($item['KeySchema']),
                    'Projection' => empty($item['Projection']) ? null : new Projection([
                        'ProjectionType' => isset($item['Projection']['ProjectionType']) ? (string) $item['Projection']['ProjectionType'] : null,
                        'NonKeyAttributes' => empty($item['Projection']['NonKeyAttributes']) ? [] : $fn['list-NonKeyAttributeNameList']($item['Projection']['NonKeyAttributes']),
                    ]),
                    'IndexStatus' => isset($item['IndexStatus']) ? (string) $item['IndexStatus'] : null,
                    'Backfilling' => isset($item['Backfilling']) ? filter_var($item['Backfilling'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'ProvisionedThroughput' => empty($item['ProvisionedThroughput']) ? null : new ProvisionedThroughputDescription([
                        'LastIncreaseDateTime' => (isset($item['ProvisionedThroughput']['LastIncreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['ProvisionedThroughput']['LastIncreaseDateTime'])))) ? $d : null,
                        'LastDecreaseDateTime' => (isset($item['ProvisionedThroughput']['LastDecreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['ProvisionedThroughput']['LastDecreaseDateTime'])))) ? $d : null,
                        'NumberOfDecreasesToday' => isset($item['ProvisionedThroughput']['NumberOfDecreasesToday']) ? (string) $item['ProvisionedThroughput']['NumberOfDecreasesToday'] : null,
                        'ReadCapacityUnits' => isset($item['ProvisionedThroughput']['ReadCapacityUnits']) ? (string) $item['ProvisionedThroughput']['ReadCapacityUnits'] : null,
                        'WriteCapacityUnits' => isset($item['ProvisionedThroughput']['WriteCapacityUnits']) ? (string) $item['ProvisionedThroughput']['WriteCapacityUnits'] : null,
                    ]),
                    'IndexSizeBytes' => isset($item['IndexSizeBytes']) ? (string) $item['IndexSizeBytes'] : null,
                    'ItemCount' => isset($item['ItemCount']) ? (string) $item['ItemCount'] : null,
                    'IndexArn' => isset($item['IndexArn']) ? (string) $item['IndexArn'] : null,
                ]);
            }

            return $items;
        };
        $fn['list-ReplicaDescriptionList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ReplicaDescription([
                    'RegionName' => isset($item['RegionName']) ? (string) $item['RegionName'] : null,
                    'ReplicaStatus' => isset($item['ReplicaStatus']) ? (string) $item['ReplicaStatus'] : null,
                    'ReplicaStatusDescription' => isset($item['ReplicaStatusDescription']) ? (string) $item['ReplicaStatusDescription'] : null,
                    'ReplicaStatusPercentProgress' => isset($item['ReplicaStatusPercentProgress']) ? (string) $item['ReplicaStatusPercentProgress'] : null,
                    'KMSMasterKeyId' => isset($item['KMSMasterKeyId']) ? (string) $item['KMSMasterKeyId'] : null,
                    'ProvisionedThroughputOverride' => empty($item['ProvisionedThroughputOverride']) ? null : new ProvisionedThroughputOverride([
                        'ReadCapacityUnits' => isset($item['ProvisionedThroughputOverride']['ReadCapacityUnits']) ? (string) $item['ProvisionedThroughputOverride']['ReadCapacityUnits'] : null,
                    ]),
                    'GlobalSecondaryIndexes' => empty($item['GlobalSecondaryIndexes']) ? [] : $fn['list-ReplicaGlobalSecondaryIndexDescriptionList']($item['GlobalSecondaryIndexes']),
                ]);
            }

            return $items;
        };
        $fn['list-ReplicaGlobalSecondaryIndexDescriptionList'] = static function (array $json) use (&$fn): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ReplicaGlobalSecondaryIndexDescription([
                    'IndexName' => isset($item['IndexName']) ? (string) $item['IndexName'] : null,
                    'ProvisionedThroughputOverride' => empty($item['ProvisionedThroughputOverride']) ? null : new ProvisionedThroughputOverride([
                        'ReadCapacityUnits' => isset($item['ProvisionedThroughputOverride']['ReadCapacityUnits']) ? (string) $item['ProvisionedThroughputOverride']['ReadCapacityUnits'] : null,
                    ]),
                ]);
            }

            return $items;
        };
        $this->TableDescription = empty($data['TableDescription']) ? null : new TableDescription([
            'AttributeDefinitions' => empty($data['TableDescription']['AttributeDefinitions']) ? [] : $fn['list-AttributeDefinitions']($data['TableDescription']['AttributeDefinitions']),
            'TableName' => isset($data['TableDescription']['TableName']) ? (string) $data['TableDescription']['TableName'] : null,
            'KeySchema' => empty($data['TableDescription']['KeySchema']) ? [] : $fn['list-KeySchema']($data['TableDescription']['KeySchema']),
            'TableStatus' => isset($data['TableDescription']['TableStatus']) ? (string) $data['TableDescription']['TableStatus'] : null,
            'CreationDateTime' => (isset($data['TableDescription']['CreationDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['CreationDateTime'])))) ? $d : null,
            'ProvisionedThroughput' => empty($data['TableDescription']['ProvisionedThroughput']) ? null : new ProvisionedThroughputDescription([
                'LastIncreaseDateTime' => (isset($data['TableDescription']['ProvisionedThroughput']['LastIncreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['ProvisionedThroughput']['LastIncreaseDateTime'])))) ? $d : null,
                'LastDecreaseDateTime' => (isset($data['TableDescription']['ProvisionedThroughput']['LastDecreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['ProvisionedThroughput']['LastDecreaseDateTime'])))) ? $d : null,
                'NumberOfDecreasesToday' => isset($data['TableDescription']['ProvisionedThroughput']['NumberOfDecreasesToday']) ? (string) $data['TableDescription']['ProvisionedThroughput']['NumberOfDecreasesToday'] : null,
                'ReadCapacityUnits' => isset($data['TableDescription']['ProvisionedThroughput']['ReadCapacityUnits']) ? (string) $data['TableDescription']['ProvisionedThroughput']['ReadCapacityUnits'] : null,
                'WriteCapacityUnits' => isset($data['TableDescription']['ProvisionedThroughput']['WriteCapacityUnits']) ? (string) $data['TableDescription']['ProvisionedThroughput']['WriteCapacityUnits'] : null,
            ]),
            'TableSizeBytes' => isset($data['TableDescription']['TableSizeBytes']) ? (string) $data['TableDescription']['TableSizeBytes'] : null,
            'ItemCount' => isset($data['TableDescription']['ItemCount']) ? (string) $data['TableDescription']['ItemCount'] : null,
            'TableArn' => isset($data['TableDescription']['TableArn']) ? (string) $data['TableDescription']['TableArn'] : null,
            'TableId' => isset($data['TableDescription']['TableId']) ? (string) $data['TableDescription']['TableId'] : null,
            'BillingModeSummary' => empty($data['TableDescription']['BillingModeSummary']) ? null : new BillingModeSummary([
                'BillingMode' => isset($data['TableDescription']['BillingModeSummary']['BillingMode']) ? (string) $data['TableDescription']['BillingModeSummary']['BillingMode'] : null,
                'LastUpdateToPayPerRequestDateTime' => (isset($data['TableDescription']['BillingModeSummary']['LastUpdateToPayPerRequestDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['BillingModeSummary']['LastUpdateToPayPerRequestDateTime'])))) ? $d : null,
            ]),
            'LocalSecondaryIndexes' => empty($data['TableDescription']['LocalSecondaryIndexes']) ? [] : $fn['list-LocalSecondaryIndexDescriptionList']($data['TableDescription']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => empty($data['TableDescription']['GlobalSecondaryIndexes']) ? [] : $fn['list-GlobalSecondaryIndexDescriptionList']($data['TableDescription']['GlobalSecondaryIndexes']),
            'StreamSpecification' => empty($data['TableDescription']['StreamSpecification']) ? null : new StreamSpecification([
                'StreamEnabled' => filter_var($data['TableDescription']['StreamSpecification']['StreamEnabled'], \FILTER_VALIDATE_BOOLEAN),
                'StreamViewType' => isset($data['TableDescription']['StreamSpecification']['StreamViewType']) ? (string) $data['TableDescription']['StreamSpecification']['StreamViewType'] : null,
            ]),
            'LatestStreamLabel' => isset($data['TableDescription']['LatestStreamLabel']) ? (string) $data['TableDescription']['LatestStreamLabel'] : null,
            'LatestStreamArn' => isset($data['TableDescription']['LatestStreamArn']) ? (string) $data['TableDescription']['LatestStreamArn'] : null,
            'GlobalTableVersion' => isset($data['TableDescription']['GlobalTableVersion']) ? (string) $data['TableDescription']['GlobalTableVersion'] : null,
            'Replicas' => empty($data['TableDescription']['Replicas']) ? [] : $fn['list-ReplicaDescriptionList']($data['TableDescription']['Replicas']),
            'RestoreSummary' => empty($data['TableDescription']['RestoreSummary']) ? null : new RestoreSummary([
                'SourceBackupArn' => isset($data['TableDescription']['RestoreSummary']['SourceBackupArn']) ? (string) $data['TableDescription']['RestoreSummary']['SourceBackupArn'] : null,
                'SourceTableArn' => isset($data['TableDescription']['RestoreSummary']['SourceTableArn']) ? (string) $data['TableDescription']['RestoreSummary']['SourceTableArn'] : null,
                'RestoreDateTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['RestoreSummary']['RestoreDateTime'])),
                'RestoreInProgress' => filter_var($data['TableDescription']['RestoreSummary']['RestoreInProgress'], \FILTER_VALIDATE_BOOLEAN),
            ]),
            'SSEDescription' => empty($data['TableDescription']['SSEDescription']) ? null : new SSEDescription([
                'Status' => isset($data['TableDescription']['SSEDescription']['Status']) ? (string) $data['TableDescription']['SSEDescription']['Status'] : null,
                'SSEType' => isset($data['TableDescription']['SSEDescription']['SSEType']) ? (string) $data['TableDescription']['SSEDescription']['SSEType'] : null,
                'KMSMasterKeyArn' => isset($data['TableDescription']['SSEDescription']['KMSMasterKeyArn']) ? (string) $data['TableDescription']['SSEDescription']['KMSMasterKeyArn'] : null,
                'InaccessibleEncryptionDateTime' => (isset($data['TableDescription']['SSEDescription']['InaccessibleEncryptionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['SSEDescription']['InaccessibleEncryptionDateTime'])))) ? $d : null,
            ]),
            'ArchivalSummary' => empty($data['TableDescription']['ArchivalSummary']) ? null : new ArchivalSummary([
                'ArchivalDateTime' => (isset($data['TableDescription']['ArchivalSummary']['ArchivalDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['TableDescription']['ArchivalSummary']['ArchivalDateTime'])))) ? $d : null,
                'ArchivalReason' => isset($data['TableDescription']['ArchivalSummary']['ArchivalReason']) ? (string) $data['TableDescription']['ArchivalSummary']['ArchivalReason'] : null,
                'ArchivalBackupArn' => isset($data['TableDescription']['ArchivalSummary']['ArchivalBackupArn']) ? (string) $data['TableDescription']['ArchivalSummary']['ArchivalBackupArn'] : null,
            ]),
        ]);
    }
}
