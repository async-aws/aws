<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateSecretResponse extends Result
{
    /**
     * The ARN of the secret that was updated.
     */
    private $arn;

    /**
     * The name of the secret that was updated.
     */
    private $name;

    /**
     * If Secrets Manager created a new version of the secret during this operation, then `VersionId` contains the unique
     * identifier of the new version.
     */
    private $versionId;

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
    }
}
