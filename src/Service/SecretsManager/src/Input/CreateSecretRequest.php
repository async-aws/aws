<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\SecretsManager\ValueObject\ReplicaRegionType;
use AsyncAws\SecretsManager\ValueObject\Tag;

final class CreateSecretRequest extends Input
{
    /**
     * Specifies the friendly name of the new secret.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * (Optional) If you include `SecretString` or `SecretBinary`, then an initial version is created as part of the secret,
     * and this parameter specifies a unique identifier for the new version.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * (Optional) Specifies a user-provided description of the secret.
     *
     * @var string|null
     */
    private $description;

    /**
     * (Optional) Specifies the ARN, Key ID, or alias of the AWS KMS customer master key (CMK) to be used to encrypt the
     * `SecretString` or `SecretBinary` values in the versions stored in this secret.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * (Optional) Specifies binary data that you want to encrypt and store in the new version of the secret. To use this
     * parameter in the command-line tools, we recommend that you store your binary data in a file and then use the
     * appropriate technique for your tool to pass the contents of the file as a parameter.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * (Optional) Specifies text data that you want to encrypt and store in this new version of the secret.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * (Optional) Specifies a list of user-defined tags that are attached to the secret. Each tag is a "Key" and "Value"
     * pair of strings. This operation only appends tags to the existing list of tags. To remove tags, you must use
     * UntagResource.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * (Optional) Add a list of regions to replicate secrets. Secrets Manager replicates the KMSKeyID objects to the list of
     * regions specified in the parameter.
     *
     * @var ReplicaRegionType[]|null
     */
    private $addReplicaRegions;

    /**
     * (Optional) If set, the replication overwrites a secret with the same name in the destination region.
     *
     * @var bool|null
     */
    private $forceOverwriteReplicaSecret;

    /**
     * @param array{
     *   Name?: string,
     *   ClientRequestToken?: string,
     *   Description?: string,
     *   KmsKeyId?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   Tags?: Tag[],
     *   AddReplicaRegions?: ReplicaRegionType[],
     *   ForceOverwriteReplicaSecret?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
        $this->secretBinary = $input['SecretBinary'] ?? null;
        $this->secretString = $input['SecretString'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->addReplicaRegions = isset($input['AddReplicaRegions']) ? array_map([ReplicaRegionType::class, 'create'], $input['AddReplicaRegions']) : null;
        $this->forceOverwriteReplicaSecret = $input['ForceOverwriteReplicaSecret'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ReplicaRegionType[]
     */
    public function getAddReplicaRegions(): array
    {
        return $this->addReplicaRegions ?? [];
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getForceOverwriteReplicaSecret(): ?bool
    {
        return $this->forceOverwriteReplicaSecret;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSecretBinary(): ?string
    {
        return $this->secretBinary;
    }

    public function getSecretString(): ?string
    {
        return $this->secretString;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.CreateSecret',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ReplicaRegionType[] $value
     */
    public function setAddReplicaRegions(array $value): self
    {
        $this->addReplicaRegions = $value;

        return $this;
    }

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setForceOverwriteReplicaSecret(?bool $value): self
    {
        $this->forceOverwriteReplicaSecret = $value;

        return $this;
    }

    public function setKmsKeyId(?string $value): self
    {
        $this->kmsKeyId = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setSecretBinary(?string $value): self
    {
        $this->secretBinary = $value;

        return $this;
    }

    public function setSecretString(?string $value): self
    {
        $this->secretString = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->kmsKeyId) {
            $payload['KmsKeyId'] = $v;
        }
        if (null !== $v = $this->secretBinary) {
            $payload['SecretBinary'] = base64_encode($v);
        }
        if (null !== $v = $this->secretString) {
            $payload['SecretString'] = $v;
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->addReplicaRegions) {
            $index = -1;
            $payload['AddReplicaRegions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AddReplicaRegions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->forceOverwriteReplicaSecret) {
            $payload['ForceOverwriteReplicaSecret'] = (bool) $v;
        }

        return $payload;
    }
}
