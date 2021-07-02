<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutSecretValueResponse extends Result
{
    /**
     * The Amazon Resource Name (ARN) for the secret for which you just created a version.
     */
    private $arn;

    /**
     * The friendly name of the secret for which you just created or updated a version.
     */
    private $name;

    /**
     * The unique identifier of the version of the secret you just created or updated.
     */
    private $versionId;

    /**
     * The list of staging labels that are currently attached to this version of the secret. Staging labels are used to
     * track a version as it progresses through the secret rotation process.
     */
    private $versionStages = [];

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
