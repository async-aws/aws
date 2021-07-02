<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSecretValueResponse extends Result
{
    /**
     * The ARN of the secret.
     */
    private $arn;

    /**
     * The friendly name of the secret.
     */
    private $name;

    /**
     * The unique identifier of this version of the secret.
     */
    private $versionId;

    /**
     * The decrypted part of the protected secret information that was originally provided as binary data in the form of a
     * byte array. The response parameter represents the binary data as a base64-encoded string.
     *
     * @see https://tools.ietf.org/html/rfc4648#section-4
     */
    private $secretBinary;

    /**
     * The decrypted part of the protected secret information that was originally provided as a string.
     */
    private $secretString;

    /**
     * A list of all of the staging labels currently attached to this version of the secret.
     */
    private $versionStages = [];

    /**
     * The date and time that this version of the secret was created.
     */
    private $createdDate;

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->arn;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->createdDate;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    public function getSecretBinary(): ?string
    {
        $this->initialize();

        return $this->secretBinary;
    }

    public function getSecretString(): ?string
    {
        $this->initialize();

        return $this->secretString;
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
        $this->secretBinary = isset($data['SecretBinary']) ? base64_decode((string) $data['SecretBinary']) : null;
        $this->secretString = isset($data['SecretString']) ? (string) $data['SecretString'] : null;
        $this->versionStages = empty($data['VersionStages']) ? [] : $this->populateResultSecretVersionStagesType($data['VersionStages']);
        $this->createdDate = (isset($data['CreatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['CreatedDate'])))) ? $d : null;
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
