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

class DescribeTableOutput extends Result
{
    /**
     * The properties of the table.
     */
    private $Table;

    public function getTable(): ?TableDescription
    {
        $this->initialize();

        return $this->Table;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Table = empty($data['Table']) ? null : new TableDescription([
            'AttributeDefinitions' => empty($data['Table']['AttributeDefinitions']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $items[] = new AttributeDefinition([
                        'AttributeName' => (string) $item['AttributeName'],
                        'AttributeType' => (string) $item['AttributeType'],
                    ]);
                }

                return $items;
            })($data['Table']['AttributeDefinitions']),
            'TableName' => isset($data['Table']['TableName']) ? (string) $data['Table']['TableName'] : null,
            'KeySchema' => empty($data['Table']['KeySchema']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $items[] = new KeySchemaElement([
                        'AttributeName' => (string) $item['AttributeName'],
                        'KeyType' => (string) $item['KeyType'],
                    ]);
                }

                return $items;
            })($data['Table']['KeySchema']),
            'TableStatus' => isset($data['Table']['TableStatus']) ? (string) $data['Table']['TableStatus'] : null,
            'CreationDateTime' => (isset($data['Table']['CreationDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['CreationDateTime'])))) ? $d : null,
            'ProvisionedThroughput' => empty($data['Table']['ProvisionedThroughput']) ? null : new ProvisionedThroughputDescription([
                'LastIncreaseDateTime' => (isset($data['Table']['ProvisionedThroughput']['LastIncreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['ProvisionedThroughput']['LastIncreaseDateTime'])))) ? $d : null,
                'LastDecreaseDateTime' => (isset($data['Table']['ProvisionedThroughput']['LastDecreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['ProvisionedThroughput']['LastDecreaseDateTime'])))) ? $d : null,
                'NumberOfDecreasesToday' => isset($data['Table']['ProvisionedThroughput']['NumberOfDecreasesToday']) ? (string) $data['Table']['ProvisionedThroughput']['NumberOfDecreasesToday'] : null,
                'ReadCapacityUnits' => isset($data['Table']['ProvisionedThroughput']['ReadCapacityUnits']) ? (string) $data['Table']['ProvisionedThroughput']['ReadCapacityUnits'] : null,
                'WriteCapacityUnits' => isset($data['Table']['ProvisionedThroughput']['WriteCapacityUnits']) ? (string) $data['Table']['ProvisionedThroughput']['WriteCapacityUnits'] : null,
            ]),
            'TableSizeBytes' => isset($data['Table']['TableSizeBytes']) ? (string) $data['Table']['TableSizeBytes'] : null,
            'ItemCount' => isset($data['Table']['ItemCount']) ? (string) $data['Table']['ItemCount'] : null,
            'TableArn' => isset($data['Table']['TableArn']) ? (string) $data['Table']['TableArn'] : null,
            'TableId' => isset($data['Table']['TableId']) ? (string) $data['Table']['TableId'] : null,
            'BillingModeSummary' => empty($data['Table']['BillingModeSummary']) ? null : new BillingModeSummary([
                'BillingMode' => isset($data['Table']['BillingModeSummary']['BillingMode']) ? (string) $data['Table']['BillingModeSummary']['BillingMode'] : null,
                'LastUpdateToPayPerRequestDateTime' => (isset($data['Table']['BillingModeSummary']['LastUpdateToPayPerRequestDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['BillingModeSummary']['LastUpdateToPayPerRequestDateTime'])))) ? $d : null,
            ]),
            'LocalSecondaryIndexes' => empty($data['Table']['LocalSecondaryIndexes']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $items[] = new LocalSecondaryIndexDescription([
                        'IndexName' => isset($item['IndexName']) ? (string) $item['IndexName'] : null,
                        'KeySchema' => empty($item['KeySchema']) ? [] : (function (array $json): array {
                            $items = [];
                            foreach ($json as $item) {
                                $items[] = new KeySchemaElement([
                                    'AttributeName' => (string) $item['AttributeName'],
                                    'KeyType' => (string) $item['KeyType'],
                                ]);
                            }

                            return $items;
                        })($item['KeySchema']),
                        'Projection' => empty($item['Projection']) ? null : new Projection([
                            'ProjectionType' => isset($item['Projection']['ProjectionType']) ? (string) $item['Projection']['ProjectionType'] : null,
                            'NonKeyAttributes' => empty($item['Projection']['NonKeyAttributes']) ? [] : (function (array $json): array {
                                $items = [];
                                foreach ($json as $item) {
                                    $a = isset($item) ? (string) $item : null;
                                    if (null !== $a) {
                                        $items[] = $a;
                                    }
                                }

                                return $items;
                            })($item['Projection']['NonKeyAttributes']),
                        ]),
                        'IndexSizeBytes' => isset($item['IndexSizeBytes']) ? (string) $item['IndexSizeBytes'] : null,
                        'ItemCount' => isset($item['ItemCount']) ? (string) $item['ItemCount'] : null,
                        'IndexArn' => isset($item['IndexArn']) ? (string) $item['IndexArn'] : null,
                    ]);
                }

                return $items;
            })($data['Table']['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => empty($data['Table']['GlobalSecondaryIndexes']) ? [] : (function (array $json): array {
                $items = [];
                foreach ($json as $item) {
                    $items[] = new GlobalSecondaryIndexDescription([
                        'IndexName' => isset($item['IndexName']) ? (string) $item['IndexName'] : null,
                        'KeySchema' => empty($item['KeySchema']) ? [] : (function (array $json): array {
                            $items = [];
                            foreach ($json as $item) {
                                $items[] = new KeySchemaElement([
                                    'AttributeName' => (string) $item['AttributeName'],
                                    'KeyType' => (string) $item['KeyType'],
                                ]);
                            }

                            return $items;
                        })($item['KeySchema']),
                        'Projection' => empty($item['Projection']) ? null : new Projection([
                            'ProjectionType' => isset($item['Projection']['ProjectionType']) ? (string) $item['Projection']['ProjectionType'] : null,
                            'NonKeyAttributes' => empty($item['Projection']['NonKeyAttributes']) ? [] : (function (array $json): array {
                                $items = [];
                                foreach ($json as $item) {
                                    $a = isset($item) ? (string) $item : null;
                                    if (null !== $a) {
                                        $items[] = $a;
                                    }
                                }

                                return $items;
                            })($item['Projection']['NonKeyAttributes']),
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
            })($data['Table']['GlobalSecondaryIndexes']),
            'StreamSpecification' => empty($data['Table']['StreamSpecification']) ? null : new StreamSpecification([
                'StreamEnabled' => filter_var($data['Table']['StreamSpecification']['StreamEnabled'], \FILTER_VALIDATE_BOOLEAN),
                'StreamViewType' => isset($data['Table']['StreamSpecification']['StreamViewType']) ? (string) $data['Table']['StreamSpecification']['StreamViewType'] : null,
            ]),
            'LatestStreamLabel' => isset($data['Table']['LatestStreamLabel']) ? (string) $data['Table']['LatestStreamLabel'] : null,
            'LatestStreamArn' => isset($data['Table']['LatestStreamArn']) ? (string) $data['Table']['LatestStreamArn'] : null,
            'GlobalTableVersion' => isset($data['Table']['GlobalTableVersion']) ? (string) $data['Table']['GlobalTableVersion'] : null,
            'Replicas' => empty($data['Table']['Replicas']) ? [] : (function (array $json): array {
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
                        'GlobalSecondaryIndexes' => empty($item['GlobalSecondaryIndexes']) ? [] : (function (array $json): array {
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
                        })($item['GlobalSecondaryIndexes']),
                    ]);
                }

                return $items;
            })($data['Table']['Replicas']),
            'RestoreSummary' => empty($data['Table']['RestoreSummary']) ? null : new RestoreSummary([
                'SourceBackupArn' => isset($data['Table']['RestoreSummary']['SourceBackupArn']) ? (string) $data['Table']['RestoreSummary']['SourceBackupArn'] : null,
                'SourceTableArn' => isset($data['Table']['RestoreSummary']['SourceTableArn']) ? (string) $data['Table']['RestoreSummary']['SourceTableArn'] : null,
                'RestoreDateTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['RestoreSummary']['RestoreDateTime'])),
                'RestoreInProgress' => filter_var($data['Table']['RestoreSummary']['RestoreInProgress'], \FILTER_VALIDATE_BOOLEAN),
            ]),
            'SSEDescription' => empty($data['Table']['SSEDescription']) ? null : new SSEDescription([
                'Status' => isset($data['Table']['SSEDescription']['Status']) ? (string) $data['Table']['SSEDescription']['Status'] : null,
                'SSEType' => isset($data['Table']['SSEDescription']['SSEType']) ? (string) $data['Table']['SSEDescription']['SSEType'] : null,
                'KMSMasterKeyArn' => isset($data['Table']['SSEDescription']['KMSMasterKeyArn']) ? (string) $data['Table']['SSEDescription']['KMSMasterKeyArn'] : null,
                'InaccessibleEncryptionDateTime' => (isset($data['Table']['SSEDescription']['InaccessibleEncryptionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['SSEDescription']['InaccessibleEncryptionDateTime'])))) ? $d : null,
            ]),
            'ArchivalSummary' => empty($data['Table']['ArchivalSummary']) ? null : new ArchivalSummary([
                'ArchivalDateTime' => (isset($data['Table']['ArchivalSummary']['ArchivalDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Table']['ArchivalSummary']['ArchivalDateTime'])))) ? $d : null,
                'ArchivalReason' => isset($data['Table']['ArchivalSummary']['ArchivalReason']) ? (string) $data['Table']['ArchivalSummary']['ArchivalReason'] : null,
                'ArchivalBackupArn' => isset($data['Table']['ArchivalSummary']['ArchivalBackupArn']) ? (string) $data['Table']['ArchivalSummary']['ArchivalBackupArn'] : null,
            ]),
        ]);
    }
}
