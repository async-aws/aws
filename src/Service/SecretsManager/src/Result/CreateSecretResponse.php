<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\SecretsManager\Enum\StatusType;
use AsyncAws\SecretsManager\ValueObject\ReplicationStatusType;

class CreateSecretResponse extends Result
{
    /**
     * The ARN of the new secret. The ARN includes the name of the secret followed by six random characters. This ensures
     * that if you create a new secret with the same name as a deleted secret, then users with access to the old secret
     * don't get access to the new secret because the ARNs are different.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The name of the new secret.
     *
     * @var string|null
     */
    private $name;

    /**
     * The unique identifier associated with the version of the new secret.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * A list of the replicas of this secret and their status:
     *
     * - `Failed`, which indicates that the replica was not created.
     * - `InProgress`, which indicates that Secrets Manager is in the process of creating the replica.
     * - `InSync`, which indicates that the replica was created.
     *
     * @var ReplicationStatusType[]
     */
    private $replicationStatus;

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
            $items[] = $this->populateResultReplicationStatusType($item);
        }

        return $items;
    }

    private function populateResultReplicationStatusType(array $json): ReplicationStatusType
    {
        return new ReplicationStatusType([
            'Region' => isset($json['Region']) ? (string) $json['Region'] : null,
            'KmsKeyId' => isset($json['KmsKeyId']) ? (string) $json['KmsKeyId'] : null,
            'Status' => isset($json['Status']) ? (!StatusType::exists((string) $json['Status']) ? StatusType::UNKNOWN_TO_SDK : (string) $json['Status']) : null,
            'StatusMessage' => isset($json['StatusMessage']) ? (string) $json['StatusMessage'] : null,
            'LastAccessedDate' => (isset($json['LastAccessedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastAccessedDate'])))) ? $d : null,
        ]);
    }
}
