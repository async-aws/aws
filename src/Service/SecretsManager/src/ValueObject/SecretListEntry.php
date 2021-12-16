<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that contains the details about a secret. It does not include the encrypted `SecretString` and
 * `SecretBinary` values. To get those values, use the GetSecretValue operation.
 */
final class SecretListEntry
{
    /**
     * The Amazon Resource Name (ARN) of the secret.
     */
    private $arn;

    /**
     * The friendly name of the secret. You can use forward slashes in the name to represent a path hierarchy. For example,
     * `/prod/databases/dbserver1` could represent the secret for a server named `dbserver1` in the folder `databases` in
     * the folder `prod`.
     */
    private $name;

    /**
     * The user-provided description of the secret.
     */
    private $description;

    /**
     * The ARN of the KMS key that Secrets Manager uses to encrypt the secret value. If the secret is encrypted with the
     * Amazon Web Services managed key `aws/secretsmanager`, this field is omitted.
     */
    private $kmsKeyId;

    /**
     * Indicates whether automatic, scheduled rotation is enabled for this secret.
     */
    private $rotationEnabled;

    /**
     * The ARN of an Amazon Web Services Lambda function invoked by Secrets Manager to rotate and expire the secret either
     * automatically per the schedule or manually by a call to RotateSecret.
     */
    private $rotationLambdaArn;

    /**
     * A structure that defines the rotation configuration for the secret.
     */
    private $rotationRules;

    /**
     * The most recent date and time that the Secrets Manager rotation process was successfully completed. This value is
     * null if the secret hasn't ever rotated.
     */
    private $lastRotatedDate;

    /**
     * The last date and time that this secret was modified in any way.
     */
    private $lastChangedDate;

    /**
     * The last date that this secret was accessed. This value is truncated to midnight of the date and therefore shows only
     * the date, not the time.
     */
    private $lastAccessedDate;

    /**
     * The date and time the deletion of the secret occurred. Not present on active secrets. The secret can be recovered
     * until the number of days in the recovery window has passed, as specified in the `RecoveryWindowInDays` parameter of
     * the DeleteSecret operation.
     */
    private $deletedDate;

    /**
     * The list of user-defined tags associated with the secret. To add tags to a secret, use TagResource. To remove tags,
     * use UntagResource.
     */
    private $tags;

    /**
     * A list of all of the currently assigned `SecretVersionStage` staging labels and the `SecretVersionId` attached to
     * each one. Staging labels are used to keep track of the different versions during the rotation process.
     */
    private $secretVersionsToStages;

    /**
     * Returns the name of the service that created the secret.
     */
    private $owningService;

    /**
     * The date and time when a secret was created.
     */
    private $createdDate;

    /**
     * The Region where Secrets Manager originated the secret.
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
     *   Tags?: null|Tag[],
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
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->secretVersionsToStages = $input['SecretVersionsToStages'] ?? null;
        $this->owningService = $input['OwningService'] ?? null;
        $this->createdDate = $input['CreatedDate'] ?? null;
        $this->primaryRegion = $input['PrimaryRegion'] ?? null;
    }

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
