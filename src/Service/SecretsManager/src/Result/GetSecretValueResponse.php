<?php

namespace AsyncAws\SecretsManager\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSecretValueResponse extends Result
{
    /**
     * The ARN of the secret.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The friendly name of the secret.
     *
     * @var string|null
     */
    private $name;

    /**
     * The unique identifier of this version of the secret.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * The decrypted secret value, if the secret value was originally provided as binary data in the form of a byte array.
     * When you retrieve a `SecretBinary` using the HTTP API, the Python SDK, or the Amazon Web Services CLI, the value is
     * Base64-encoded. Otherwise, it is not encoded.
     *
     * If the secret was created by using the Secrets Manager console, or if the secret value was originally provided as a
     * string, then this field is omitted. The secret value appears in `SecretString` instead.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The decrypted secret value, if the secret value was originally provided as a string or through the Secrets Manager
     * console.
     *
     * If this secret was created by using the console, then Secrets Manager stores the information as a JSON structure of
     * key/value pairs.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * A list of all of the staging labels currently attached to this version of the secret.
     *
     * @var string[]
     */
    private $versionStages;

    /**
     * The date and time that this version of the secret was created. If you don't specify which version in `VersionId` or
     * `VersionStage`, then Secrets Manager uses the `AWSCURRENT` version.
     *
     * @var \DateTimeImmutable|null
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
        $this->createdDate = (isset($data['CreatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['CreatedDate'])))) ? $d : null;
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
