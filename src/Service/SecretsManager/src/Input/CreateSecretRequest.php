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
     * The name of the new secret.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * If you include `SecretString` or `SecretBinary`, then Secrets Manager creates an initial version for the secret, and
     * this parameter specifies the unique identifier for the new version.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * The description of the secret.
     *
     * @var string|null
     */
    private $description;

    /**
     * The ARN, key ID, or alias of the KMS key that Secrets Manager uses to encrypt the secret value in the secret.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The binary data to encrypt and store in the new version of the secret. We recommend that you store your binary data
     * in a file and then pass the contents of the file as a parameter.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The text data to encrypt and store in this new version of the secret. We recommend you use a JSON structure of
     * key/value pairs for your secret value.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * A list of tags to attach to the secret. Each tag is a key and value pair of strings in a JSON text string, for
     * example:.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * A list of Regions and KMS keys to replicate secrets.
     *
     * @var ReplicaRegionType[]|null
     */
    private $addReplicaRegions;

    /**
     * Specifies whether to overwrite a secret with the same name in the destination Region.
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
        if (null === $v = $this->clientRequestToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientRequestToken'] = $v;
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
