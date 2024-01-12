<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that contains the details about a secret. It does not include the encrypted `SecretString` and
 * `SecretBinary` values. To get those values, use GetSecretValue [^1] .
 *
 * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_GetSecretValue.html
 */
final class SecretListEntry
{
    /**
     * The Amazon Resource Name (ARN) of the secret.
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
     * The user-provided description of the secret.
     *
     * @var string|null
     */
    private $description;

    /**
     * The ARN of the KMS key that Secrets Manager uses to encrypt the secret value. If the secret is encrypted with the
     * Amazon Web Services managed key `aws/secretsmanager`, this field is omitted.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * Indicates whether automatic, scheduled rotation is enabled for this secret.
     *
     * @var bool|null
     */
    private $rotationEnabled;

    /**
     * The ARN of an Amazon Web Services Lambda function invoked by Secrets Manager to rotate and expire the secret either
     * automatically per the schedule or manually by a call to `RotateSecret` [^1].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_RotateSecret.html
     *
     * @var string|null
     */
    private $rotationLambdaArn;

    /**
     * A structure that defines the rotation configuration for the secret.
     *
     * @var RotationRulesType|null
     */
    private $rotationRules;

    /**
     * The most recent date and time that the Secrets Manager rotation process was successfully completed. This value is
     * null if the secret hasn't ever rotated.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastRotatedDate;

    /**
     * The last date and time that this secret was modified in any way.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastChangedDate;

    /**
     * The date that the secret was last accessed in the Region. This field is omitted if the secret has never been
     * retrieved in the Region.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastAccessedDate;

    /**
     * The date and time the deletion of the secret occurred. Not present on active secrets. The secret can be recovered
     * until the number of days in the recovery window has passed, as specified in the `RecoveryWindowInDays` parameter of
     * the `DeleteSecret` [^1] operation.
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_DeleteSecret.html
     *
     * @var \DateTimeImmutable|null
     */
    private $deletedDate;

    /**
     * The next rotation is scheduled to occur on or before this date. If the secret isn't configured for rotation or
     * rotation has been disabled, Secrets Manager returns null.
     *
     * @var \DateTimeImmutable|null
     */
    private $nextRotationDate;

    /**
     * The list of user-defined tags associated with the secret. To add tags to a secret, use `TagResource` [^1]. To remove
     * tags, use `UntagResource` [^2].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_TagResource.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_UntagResource.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * A list of all of the currently assigned `SecretVersionStage` staging labels and the `SecretVersionId` attached to
     * each one. Staging labels are used to keep track of the different versions during the rotation process.
     *
     * > A version that does not have any `SecretVersionStage` is considered deprecated and subject to deletion. Such
     * > versions are not included in this list.
     *
     * @var array<string, string[]>|null
     */
    private $secretVersionsToStages;

    /**
     * Returns the name of the service that created the secret.
     *
     * @var string|null
     */
    private $owningService;

    /**
     * The date and time when a secret was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $createdDate;

    /**
     * The Region where Secrets Manager originated the secret.
     *
     * @var string|null
     */
    private $primaryRegion;

    /**
     * @param array{
     *   ARN?: null|string,
     *   Name?: null|string,
     *   Description?: null|string,
     *   KmsKeyId?: null|string,
     *   RotationEnabled?: null|bool,
     *   RotationLambdaARN?: null|string,
     *   RotationRules?: null|RotationRulesType|array,
     *   LastRotatedDate?: null|\DateTimeImmutable,
     *   LastChangedDate?: null|\DateTimeImmutable,
     *   LastAccessedDate?: null|\DateTimeImmutable,
     *   DeletedDate?: null|\DateTimeImmutable,
     *   NextRotationDate?: null|\DateTimeImmutable,
     *   Tags?: null|array<Tag|array>,
     *   SecretVersionsToStages?: null|array<string, array>,
     *   OwningService?: null|string,
     *   CreatedDate?: null|\DateTimeImmutable,
     *   PrimaryRegion?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['ARN'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
        $this->rotationEnabled = $input['RotationEnabled'] ?? null;
        $this->rotationLambdaArn = $input['RotationLambdaARN'] ?? null;
        $this->rotationRules = isset($input['RotationRules']) ? RotationRulesType::create($input['RotationRules']) : null;
        $this->lastRotatedDate = $input['LastRotatedDate'] ?? null;
        $this->lastChangedDate = $input['LastChangedDate'] ?? null;
        $this->lastAccessedDate = $input['LastAccessedDate'] ?? null;
        $this->deletedDate = $input['DeletedDate'] ?? null;
        $this->nextRotationDate = $input['NextRotationDate'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->secretVersionsToStages = $input['SecretVersionsToStages'] ?? null;
        $this->owningService = $input['OwningService'] ?? null;
        $this->createdDate = $input['CreatedDate'] ?? null;
        $this->primaryRegion = $input['PrimaryRegion'] ?? null;
    }

    /**
     * @param array{
     *   ARN?: null|string,
     *   Name?: null|string,
     *   Description?: null|string,
     *   KmsKeyId?: null|string,
     *   RotationEnabled?: null|bool,
     *   RotationLambdaARN?: null|string,
     *   RotationRules?: null|RotationRulesType|array,
     *   LastRotatedDate?: null|\DateTimeImmutable,
     *   LastChangedDate?: null|\DateTimeImmutable,
     *   LastAccessedDate?: null|\DateTimeImmutable,
     *   DeletedDate?: null|\DateTimeImmutable,
     *   NextRotationDate?: null|\DateTimeImmutable,
     *   Tags?: null|array<Tag|array>,
     *   SecretVersionsToStages?: null|array<string, array>,
     *   OwningService?: null|string,
     *   CreatedDate?: null|\DateTimeImmutable,
     *   PrimaryRegion?: null|string,
     * }|SecretListEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function getDeletedDate(): ?\DateTimeImmutable
    {
        return $this->deletedDate;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getLastAccessedDate(): ?\DateTimeImmutable
    {
        return $this->lastAccessedDate;
    }

    public function getLastChangedDate(): ?\DateTimeImmutable
    {
        return $this->lastChangedDate;
    }

    public function getLastRotatedDate(): ?\DateTimeImmutable
    {
        return $this->lastRotatedDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNextRotationDate(): ?\DateTimeImmutable
    {
        return $this->nextRotationDate;
    }

    public function getOwningService(): ?string
    {
        return $this->owningService;
    }

    public function getPrimaryRegion(): ?string
    {
        return $this->primaryRegion;
    }

    public function getRotationEnabled(): ?bool
    {
        return $this->rotationEnabled;
    }

    public function getRotationLambdaArn(): ?string
    {
        return $this->rotationLambdaArn;
    }

    public function getRotationRules(): ?RotationRulesType
    {
        return $this->rotationRules;
    }

    /**
     * @return array<string, string[]>
     */
    public function getSecretVersionsToStages(): array
    {
        return $this->secretVersionsToStages ?? [];
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }
}
