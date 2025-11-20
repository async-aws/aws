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
     * The secret name can contain ASCII letters, numbers, and the following characters: /_+=.@-
     *
     * Do not end your secret name with a hyphen followed by six characters. If you do so, you risk confusion and unexpected
     * results when searching for a secret by partial ARN. Secrets Manager automatically adds a hyphen and six random
     * characters after the secret name at the end of the ARN.
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
     * > If you use the Amazon Web Services CLI or one of the Amazon Web Services SDKs to call this operation, then you can
     * > leave this parameter empty. The CLI or SDK generates a random UUID for you and includes it as the value for this
     * > parameter in the request.
     *
     * If you generate a raw HTTP request to the Secrets Manager service endpoint, then you must generate a
     * `ClientRequestToken` and include it in the request.
     *
     * This value helps ensure idempotency. Secrets Manager uses this value to prevent the accidental creation of duplicate
     * versions if there are failures and retries during a rotation. We recommend that you generate a UUID-type [^1] value
     * to ensure uniqueness of your versions within the specified secret.
     *
     * - If the `ClientRequestToken` value isn't already associated with a version of the secret then a new version of the
     *   secret is created.
     * - If a version with this value already exists and the version `SecretString` and `SecretBinary` values are the same
     *   as those in the request, then the request is ignored.
     * - If a version with this value already exists and that version's `SecretString` and `SecretBinary` values are
     *   different from those in the request, then the request fails because you cannot modify an existing version. Instead,
     *   use PutSecretValue to create a new version.
     *
     * This value becomes the `VersionId` of the new version.
     *
     * [^1]: https://wikipedia.org/wiki/Universally_unique_identifier
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
     * The ARN, key ID, or alias of the KMS key that Secrets Manager uses to encrypt the secret value in the secret. An
     * alias is always prefixed by `alias/`, for example `alias/aws/secretsmanager`. For more information, see About aliases
     * [^1].
     *
     * To use a KMS key in a different account, use the key ARN or the alias ARN.
     *
     * If you don't specify this value, then Secrets Manager uses the key `aws/secretsmanager`. If that key doesn't yet
     * exist, then Secrets Manager creates it for you automatically the first time it encrypts the secret value.
     *
     * If the secret is in a different Amazon Web Services account from the credentials calling the API, then you can't use
     * `aws/secretsmanager` to encrypt the secret, and you must create and use a customer managed KMS key.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/alias-about.html
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The binary data to encrypt and store in the new version of the secret. We recommend that you store your binary data
     * in a file and then pass the contents of the file as a parameter.
     *
     * Either `SecretString` or `SecretBinary` must have a value, but not both.
     *
     * This parameter is not available in the Secrets Manager console.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The text data to encrypt and store in this new version of the secret. We recommend you use a JSON structure of
     * key/value pairs for your secret value.
     *
     * Either `SecretString` or `SecretBinary` must have a value, but not both.
     *
     * If you create a secret by using the Secrets Manager console then Secrets Manager puts the protected secret text in
     * only the `SecretString` parameter. The Secrets Manager console stores the information as a JSON structure of
     * key/value pairs that a Lambda rotation function can parse.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * A list of tags to attach to the secret. Each tag is a key and value pair of strings in a JSON text string, for
     * example:
     *
     * `[{"Key":"CostCenter","Value":"12345"},{"Key":"environment","Value":"production"}]`
     *
     * Secrets Manager tag key names are case sensitive. A tag with the key "ABC" is a different tag from one with key
     * "abc".
     *
     * If you check tags in permissions policies as part of your security strategy, then adding or removing a tag can change
     * permissions. If the completion of this operation would result in you losing your permissions for this secret, then
     * Secrets Manager blocks the operation and returns an `Access Denied` error. For more information, see Control access
     * to secrets using tags [^1] and Limit access to identities with tags that match secrets' tags [^2].
     *
     * For information about how to format a JSON parameter for the various command line tool environments, see Using JSON
     * for Parameters [^3]. If your command-line tool or SDK requires quotation marks around the parameter, you should use
     * single quotes to avoid confusion with the double quotes required in the JSON text.
     *
     * For tag quotas and naming restrictions, see Service quotas for Tagging [^4] in the *Amazon Web Services General
     * Reference guide*.
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access_examples.html#tag-secrets-abac
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/auth-and-access_examples.html#auth-and-access_tags2
     * [^3]: https://docs.aws.amazon.com/cli/latest/userguide/cli-using-param.html#cli-using-param-json
     * [^4]: https://docs.aws.amazon.com/general/latest/gr/arg.html#taged-reference-quotas
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
     * Specifies whether to overwrite a secret with the same name in the destination Region. By default, secrets aren't
     * overwritten.
     *
     * @var bool|null
     */
    private $forceOverwriteReplicaSecret;

    /**
     * The exact string that identifies the partner that holds the external secret. For more information, see Using Secrets
     * Manager managed external secrets [^1].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/managed-external-secrets.html
     *
     * @var string|null
     */
    private $type;

    /**
     * @param array{
     *   Name?: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   AddReplicaRegions?: array<ReplicaRegionType|array>|null,
     *   ForceOverwriteReplicaSecret?: bool|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
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
        $this->type = $input['Type'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Name?: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   AddReplicaRegions?: array<ReplicaRegionType|array>|null,
     *   ForceOverwriteReplicaSecret?: bool|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
     * }|CreateSecretRequest $input
     */
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

    public function getType(): ?string
    {
        return $this->type;
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
            'Accept' => 'application/json',
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

    public function setType(?string $value): self
    {
        $this->type = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
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
        if (null !== $v = $this->type) {
            $payload['Type'] = $v;
        }

        return $payload;
    }
}
