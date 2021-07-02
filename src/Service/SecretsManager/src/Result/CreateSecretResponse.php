<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\SecretsManager\ValueObject\ReplicationStatusType;

class CreateSecretResponse extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the secret that you just created.
     */
    private $arn;

    /**
     * The friendly name of the secret that you just created.
     */
    private $name;

    /**
     * The unique identifier associated with the version of the secret you just created.
     */
    private $versionId;

    /**
     * Describes a list of replication status objects as `InProgress`, `Failed` or `InSync`.
     */
    private $replicationStatus = [];

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->arn;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    /**
     * @return ReplicationStatusType[]
     */
    public function getReplicationStatus(): array
    {
        $this->initialize();

        return $this->replicationStatus;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->arn = isset($data['ARN']) ? (string) $data['ARN'] : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->versionId = isset($data['VersionId']) ? (string) $data['VersionId'] : null;
        $this->replicationStatus = empty($data['ReplicationStatus']) ? [] : $this->populateResultReplicationStatusListType($data['ReplicationStatus']);
    }

    /**
     * @return ReplicationStatusType[]
     */
    private function populateResultReplicationStatusListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ReplicationStatusType([
                'Region' => isset($item['Region']) ? (string) $item['Region'] : null,
                'KmsKeyId' => isset($item['KmsKeyId']) ? (string) $item['KmsKeyId'] : null,
                'Status' => isset($item['Status']) ? (string) $item['Status'] : null,
                'StatusMessage' => isset($item['StatusMessage']) ? (string) $item['StatusMessage'] : null,
                'LastAccessedDate' => (isset($item['LastAccessedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastAccessedDate'])))) ? $d : null,
            ]);
        }

        return $items;
    }
}
