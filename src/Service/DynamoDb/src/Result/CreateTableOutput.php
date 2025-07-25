<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\ArchivalSummary;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\BillingModeSummary;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexWarmThroughputDescription;
use AsyncAws\DynamoDb\ValueObject\GlobalTableWitnessDescription;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\OnDemandThroughput;
use AsyncAws\DynamoDb\ValueObject\OnDemandThroughputOverride;
use AsyncAws\DynamoDb\ValueObject\Projection;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputDescription;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputOverride;
use AsyncAws\DynamoDb\ValueObject\ReplicaDescription;
use AsyncAws\DynamoDb\ValueObject\ReplicaGlobalSecondaryIndexDescription;
use AsyncAws\DynamoDb\ValueObject\RestoreSummary;
use AsyncAws\DynamoDb\ValueObject\SSEDescription;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\TableClassSummary;
use AsyncAws\DynamoDb\ValueObject\TableDescription;
use AsyncAws\DynamoDb\ValueObject\TableWarmThroughputDescription;

/**
 * Represents the output of a `CreateTable` operation.
 */
class CreateTableOutput extends Result
{
    /**
     * Represents the properties of the table.
     *
     * @var TableDescription|null
     */
    private $tableDescription;

    public function getTableDescription(): ?TableDescription
    {
        $this->initialize();

        return $this->tableDescription;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->tableDescription = empty($data['TableDescription']) ? null : $this->populateResultTableDescription($data['TableDescription']);
    }

    private function populateResultArchivalSummary(array $json): ArchivalSummary
    {
        return new ArchivalSummary([
            'ArchivalDateTime' => (isset($json['ArchivalDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['ArchivalDateTime'])))) ? $d : null,
            'ArchivalReason' => isset($json['ArchivalReason']) ? (string) $json['ArchivalReason'] : null,
            'ArchivalBackupArn' => isset($json['ArchivalBackupArn']) ? (string) $json['ArchivalBackupArn'] : null,
        ]);
    }

    private function populateResultAttributeDefinition(array $json): AttributeDefinition
    {
        return new AttributeDefinition([
            'AttributeName' => (string) $json['AttributeName'],
            'AttributeType' => (string) $json['AttributeType'],
        ]);
    }

    /**
     * @return AttributeDefinition[]
     */
    private function populateResultAttributeDefinitions(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAttributeDefinition($item);
        }

        return $items;
    }

    private function populateResultBillingModeSummary(array $json): BillingModeSummary
    {
        return new BillingModeSummary([
            'BillingMode' => isset($json['BillingMode']) ? (string) $json['BillingMode'] : null,
            'LastUpdateToPayPerRequestDateTime' => (isset($json['LastUpdateToPayPerRequestDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastUpdateToPayPerRequestDateTime'])))) ? $d : null,
        ]);
    }

    private function populateResultGlobalSecondaryIndexDescription(array $json): GlobalSecondaryIndexDescription
    {
        return new GlobalSecondaryIndexDescription([
            'IndexName' => isset($json['IndexName']) ? (string) $json['IndexName'] : null,
            'KeySchema' => !isset($json['KeySchema']) ? null : $this->populateResultKeySchema($json['KeySchema']),
            'Projection' => empty($json['Projection']) ? null : $this->populateResultProjection($json['Projection']),
            'IndexStatus' => isset($json['IndexStatus']) ? (string) $json['IndexStatus'] : null,
            'Backfilling' => isset($json['Backfilling']) ? filter_var($json['Backfilling'], \FILTER_VALIDATE_BOOLEAN) : null,
            'ProvisionedThroughput' => empty($json['ProvisionedThroughput']) ? null : $this->populateResultProvisionedThroughputDescription($json['ProvisionedThroughput']),
            'IndexSizeBytes' => isset($json['IndexSizeBytes']) ? (int) $json['IndexSizeBytes'] : null,
            'ItemCount' => isset($json['ItemCount']) ? (int) $json['ItemCount'] : null,
            'IndexArn' => isset($json['IndexArn']) ? (string) $json['IndexArn'] : null,
            'OnDemandThroughput' => empty($json['OnDemandThroughput']) ? null : $this->populateResultOnDemandThroughput($json['OnDemandThroughput']),
            'WarmThroughput' => empty($json['WarmThroughput']) ? null : $this->populateResultGlobalSecondaryIndexWarmThroughputDescription($json['WarmThroughput']),
        ]);
    }

    /**
     * @return GlobalSecondaryIndexDescription[]
     */
    private function populateResultGlobalSecondaryIndexDescriptionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultGlobalSecondaryIndexDescription($item);
        }

        return $items;
    }

    private function populateResultGlobalSecondaryIndexWarmThroughputDescription(array $json): GlobalSecondaryIndexWarmThroughputDescription
    {
        return new GlobalSecondaryIndexWarmThroughputDescription([
            'ReadUnitsPerSecond' => isset($json['ReadUnitsPerSecond']) ? (int) $json['ReadUnitsPerSecond'] : null,
            'WriteUnitsPerSecond' => isset($json['WriteUnitsPerSecond']) ? (int) $json['WriteUnitsPerSecond'] : null,
            'Status' => isset($json['Status']) ? (string) $json['Status'] : null,
        ]);
    }

    private function populateResultGlobalTableWitnessDescription(array $json): GlobalTableWitnessDescription
    {
        return new GlobalTableWitnessDescription([
            'RegionName' => isset($json['RegionName']) ? (string) $json['RegionName'] : null,
            'WitnessStatus' => isset($json['WitnessStatus']) ? (string) $json['WitnessStatus'] : null,
        ]);
    }

    /**
     * @return GlobalTableWitnessDescription[]
     */
    private function populateResultGlobalTableWitnessDescriptionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultGlobalTableWitnessDescription($item);
        }

        return $items;
    }

    /**
     * @return KeySchemaElement[]
     */
    private function populateResultKeySchema(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultKeySchemaElement($item);
        }

        return $items;
    }

    private function populateResultKeySchemaElement(array $json): KeySchemaElement
    {
        return new KeySchemaElement([
            'AttributeName' => (string) $json['AttributeName'],
            'KeyType' => (string) $json['KeyType'],
        ]);
    }

    private function populateResultLocalSecondaryIndexDescription(array $json): LocalSecondaryIndexDescription
    {
        return new LocalSecondaryIndexDescription([
            'IndexName' => isset($json['IndexName']) ? (string) $json['IndexName'] : null,
            'KeySchema' => !isset($json['KeySchema']) ? null : $this->populateResultKeySchema($json['KeySchema']),
            'Projection' => empty($json['Projection']) ? null : $this->populateResultProjection($json['Projection']),
            'IndexSizeBytes' => isset($json['IndexSizeBytes']) ? (int) $json['IndexSizeBytes'] : null,
            'ItemCount' => isset($json['ItemCount']) ? (int) $json['ItemCount'] : null,
            'IndexArn' => isset($json['IndexArn']) ? (string) $json['IndexArn'] : null,
        ]);
    }

    /**
     * @return LocalSecondaryIndexDescription[]
     */
    private function populateResultLocalSecondaryIndexDescriptionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLocalSecondaryIndexDescription($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultNonKeyAttributeNameList(array $json): array
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

    private function populateResultOnDemandThroughput(array $json): OnDemandThroughput
    {
        return new OnDemandThroughput([
            'MaxReadRequestUnits' => isset($json['MaxReadRequestUnits']) ? (int) $json['MaxReadRequestUnits'] : null,
            'MaxWriteRequestUnits' => isset($json['MaxWriteRequestUnits']) ? (int) $json['MaxWriteRequestUnits'] : null,
        ]);
    }

    private function populateResultOnDemandThroughputOverride(array $json): OnDemandThroughputOverride
    {
        return new OnDemandThroughputOverride([
            'MaxReadRequestUnits' => isset($json['MaxReadRequestUnits']) ? (int) $json['MaxReadRequestUnits'] : null,
        ]);
    }

    private function populateResultProjection(array $json): Projection
    {
        return new Projection([
            'ProjectionType' => isset($json['ProjectionType']) ? (string) $json['ProjectionType'] : null,
            'NonKeyAttributes' => !isset($json['NonKeyAttributes']) ? null : $this->populateResultNonKeyAttributeNameList($json['NonKeyAttributes']),
        ]);
    }

    private function populateResultProvisionedThroughputDescription(array $json): ProvisionedThroughputDescription
    {
        return new ProvisionedThroughputDescription([
            'LastIncreaseDateTime' => (isset($json['LastIncreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastIncreaseDateTime'])))) ? $d : null,
            'LastDecreaseDateTime' => (isset($json['LastDecreaseDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastDecreaseDateTime'])))) ? $d : null,
            'NumberOfDecreasesToday' => isset($json['NumberOfDecreasesToday']) ? (int) $json['NumberOfDecreasesToday'] : null,
            'ReadCapacityUnits' => isset($json['ReadCapacityUnits']) ? (int) $json['ReadCapacityUnits'] : null,
            'WriteCapacityUnits' => isset($json['WriteCapacityUnits']) ? (int) $json['WriteCapacityUnits'] : null,
        ]);
    }

    private function populateResultProvisionedThroughputOverride(array $json): ProvisionedThroughputOverride
    {
        return new ProvisionedThroughputOverride([
            'ReadCapacityUnits' => isset($json['ReadCapacityUnits']) ? (int) $json['ReadCapacityUnits'] : null,
        ]);
    }

    private function populateResultReplicaDescription(array $json): ReplicaDescription
    {
        return new ReplicaDescription([
            'RegionName' => isset($json['RegionName']) ? (string) $json['RegionName'] : null,
            'ReplicaStatus' => isset($json['ReplicaStatus']) ? (string) $json['ReplicaStatus'] : null,
            'ReplicaStatusDescription' => isset($json['ReplicaStatusDescription']) ? (string) $json['ReplicaStatusDescription'] : null,
            'ReplicaStatusPercentProgress' => isset($json['ReplicaStatusPercentProgress']) ? (string) $json['ReplicaStatusPercentProgress'] : null,
            'KMSMasterKeyId' => isset($json['KMSMasterKeyId']) ? (string) $json['KMSMasterKeyId'] : null,
            'ProvisionedThroughputOverride' => empty($json['ProvisionedThroughputOverride']) ? null : $this->populateResultProvisionedThroughputOverride($json['ProvisionedThroughputOverride']),
            'OnDemandThroughputOverride' => empty($json['OnDemandThroughputOverride']) ? null : $this->populateResultOnDemandThroughputOverride($json['OnDemandThroughputOverride']),
            'WarmThroughput' => empty($json['WarmThroughput']) ? null : $this->populateResultTableWarmThroughputDescription($json['WarmThroughput']),
            'GlobalSecondaryIndexes' => !isset($json['GlobalSecondaryIndexes']) ? null : $this->populateResultReplicaGlobalSecondaryIndexDescriptionList($json['GlobalSecondaryIndexes']),
            'ReplicaInaccessibleDateTime' => (isset($json['ReplicaInaccessibleDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['ReplicaInaccessibleDateTime'])))) ? $d : null,
            'ReplicaTableClassSummary' => empty($json['ReplicaTableClassSummary']) ? null : $this->populateResultTableClassSummary($json['ReplicaTableClassSummary']),
        ]);
    }

    /**
     * @return ReplicaDescription[]
     */
    private function populateResultReplicaDescriptionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultReplicaDescription($item);
        }

        return $items;
    }

    private function populateResultReplicaGlobalSecondaryIndexDescription(array $json): ReplicaGlobalSecondaryIndexDescription
    {
        return new ReplicaGlobalSecondaryIndexDescription([
            'IndexName' => isset($json['IndexName']) ? (string) $json['IndexName'] : null,
            'ProvisionedThroughputOverride' => empty($json['ProvisionedThroughputOverride']) ? null : $this->populateResultProvisionedThroughputOverride($json['ProvisionedThroughputOverride']),
            'OnDemandThroughputOverride' => empty($json['OnDemandThroughputOverride']) ? null : $this->populateResultOnDemandThroughputOverride($json['OnDemandThroughputOverride']),
            'WarmThroughput' => empty($json['WarmThroughput']) ? null : $this->populateResultGlobalSecondaryIndexWarmThroughputDescription($json['WarmThroughput']),
        ]);
    }

    /**
     * @return ReplicaGlobalSecondaryIndexDescription[]
     */
    private function populateResultReplicaGlobalSecondaryIndexDescriptionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultReplicaGlobalSecondaryIndexDescription($item);
        }

        return $items;
    }

    private function populateResultRestoreSummary(array $json): RestoreSummary
    {
        return new RestoreSummary([
            'SourceBackupArn' => isset($json['SourceBackupArn']) ? (string) $json['SourceBackupArn'] : null,
            'SourceTableArn' => isset($json['SourceTableArn']) ? (string) $json['SourceTableArn'] : null,
            'RestoreDateTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['RestoreDateTime'])),
            'RestoreInProgress' => filter_var($json['RestoreInProgress'], \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    private function populateResultSSEDescription(array $json): SSEDescription
    {
        return new SSEDescription([
            'Status' => isset($json['Status']) ? (string) $json['Status'] : null,
            'SSEType' => isset($json['SSEType']) ? (string) $json['SSEType'] : null,
            'KMSMasterKeyArn' => isset($json['KMSMasterKeyArn']) ? (string) $json['KMSMasterKeyArn'] : null,
            'InaccessibleEncryptionDateTime' => (isset($json['InaccessibleEncryptionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['InaccessibleEncryptionDateTime'])))) ? $d : null,
        ]);
    }

    private function populateResultStreamSpecification(array $json): StreamSpecification
    {
        return new StreamSpecification([
            'StreamEnabled' => filter_var($json['StreamEnabled'], \FILTER_VALIDATE_BOOLEAN),
            'StreamViewType' => isset($json['StreamViewType']) ? (string) $json['StreamViewType'] : null,
        ]);
    }

    private function populateResultTableClassSummary(array $json): TableClassSummary
    {
        return new TableClassSummary([
            'TableClass' => isset($json['TableClass']) ? (string) $json['TableClass'] : null,
            'LastUpdateDateTime' => (isset($json['LastUpdateDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastUpdateDateTime'])))) ? $d : null,
        ]);
    }

    private function populateResultTableDescription(array $json): TableDescription
    {
        return new TableDescription([
            'AttributeDefinitions' => !isset($json['AttributeDefinitions']) ? null : $this->populateResultAttributeDefinitions($json['AttributeDefinitions']),
            'TableName' => isset($json['TableName']) ? (string) $json['TableName'] : null,
            'KeySchema' => !isset($json['KeySchema']) ? null : $this->populateResultKeySchema($json['KeySchema']),
            'TableStatus' => isset($json['TableStatus']) ? (string) $json['TableStatus'] : null,
            'CreationDateTime' => (isset($json['CreationDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDateTime'])))) ? $d : null,
            'ProvisionedThroughput' => empty($json['ProvisionedThroughput']) ? null : $this->populateResultProvisionedThroughputDescription($json['ProvisionedThroughput']),
            'TableSizeBytes' => isset($json['TableSizeBytes']) ? (int) $json['TableSizeBytes'] : null,
            'ItemCount' => isset($json['ItemCount']) ? (int) $json['ItemCount'] : null,
            'TableArn' => isset($json['TableArn']) ? (string) $json['TableArn'] : null,
            'TableId' => isset($json['TableId']) ? (string) $json['TableId'] : null,
            'BillingModeSummary' => empty($json['BillingModeSummary']) ? null : $this->populateResultBillingModeSummary($json['BillingModeSummary']),
            'LocalSecondaryIndexes' => !isset($json['LocalSecondaryIndexes']) ? null : $this->populateResultLocalSecondaryIndexDescriptionList($json['LocalSecondaryIndexes']),
            'GlobalSecondaryIndexes' => !isset($json['GlobalSecondaryIndexes']) ? null : $this->populateResultGlobalSecondaryIndexDescriptionList($json['GlobalSecondaryIndexes']),
            'StreamSpecification' => empty($json['StreamSpecification']) ? null : $this->populateResultStreamSpecification($json['StreamSpecification']),
            'LatestStreamLabel' => isset($json['LatestStreamLabel']) ? (string) $json['LatestStreamLabel'] : null,
            'LatestStreamArn' => isset($json['LatestStreamArn']) ? (string) $json['LatestStreamArn'] : null,
            'GlobalTableVersion' => isset($json['GlobalTableVersion']) ? (string) $json['GlobalTableVersion'] : null,
            'Replicas' => !isset($json['Replicas']) ? null : $this->populateResultReplicaDescriptionList($json['Replicas']),
            'GlobalTableWitnesses' => !isset($json['GlobalTableWitnesses']) ? null : $this->populateResultGlobalTableWitnessDescriptionList($json['GlobalTableWitnesses']),
            'RestoreSummary' => empty($json['RestoreSummary']) ? null : $this->populateResultRestoreSummary($json['RestoreSummary']),
            'SSEDescription' => empty($json['SSEDescription']) ? null : $this->populateResultSSEDescription($json['SSEDescription']),
            'ArchivalSummary' => empty($json['ArchivalSummary']) ? null : $this->populateResultArchivalSummary($json['ArchivalSummary']),
            'TableClassSummary' => empty($json['TableClassSummary']) ? null : $this->populateResultTableClassSummary($json['TableClassSummary']),
            'DeletionProtectionEnabled' => isset($json['DeletionProtectionEnabled']) ? filter_var($json['DeletionProtectionEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'OnDemandThroughput' => empty($json['OnDemandThroughput']) ? null : $this->populateResultOnDemandThroughput($json['OnDemandThroughput']),
            'WarmThroughput' => empty($json['WarmThroughput']) ? null : $this->populateResultTableWarmThroughputDescription($json['WarmThroughput']),
            'MultiRegionConsistency' => isset($json['MultiRegionConsistency']) ? (string) $json['MultiRegionConsistency'] : null,
        ]);
    }

    private function populateResultTableWarmThroughputDescription(array $json): TableWarmThroughputDescription
    {
        return new TableWarmThroughputDescription([
            'ReadUnitsPerSecond' => isset($json['ReadUnitsPerSecond']) ? (int) $json['ReadUnitsPerSecond'] : null,
            'WriteUnitsPerSecond' => isset($json['WriteUnitsPerSecond']) ? (int) $json['WriteUnitsPerSecond'] : null,
            'Status' => isset($json['Status']) ? (string) $json['Status'] : null,
        ]);
    }
}
