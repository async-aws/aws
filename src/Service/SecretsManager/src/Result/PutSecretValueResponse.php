<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutSecretValueResponse extends Result
{
    /**
     * The ARN of the secret.
     */
    private $arn;

    /**
     * The name of the secret.
     */
    private $name;

    /**
     * The unique identifier of the version of the secret.
     */
    private $versionId;

    /**
     * The list of staging labels that are currently attached to this version of the secret. Secrets Manager uses staging
     * labels to track a version as it progresses through the secret rotation process.
     */
    private $versionStages;

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

    /**
     * @return string[]
     */
    public function getVersionStages(): array
    {
        $this->initialize();

        return $this->versionStages;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->arn = isset($data['ARN']) ? (string) $data['ARN'] : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->versionId = isset($data['VersionId']) ? (string) $data['VersionId'] : null;
        $this->versionStages = empty($data['VersionStages']) ? [] : $this->populateResultSecretVersionStagesType($data['VersionStages']);
    }

    /**
     * @return string[]
     */
    private function populateResultSecretVersionStagesType(array $json): array
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
