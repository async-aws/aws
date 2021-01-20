<?php

namespace AsyncAws\Iam\ValueObject;

/**
 * A structure with details about the new IAM user.
 */
final class User
{
    /**
     * The path to the user. For more information about paths, see IAM Identifiers in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $path;

    /**
     * The friendly name identifying the user.
     */
    private $userName;

    /**
     * The stable and unique string identifying the user. For more information about IDs, see IAM Identifiers in the *IAM
     * User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $userId;

    /**
     * The Amazon Resource Name (ARN) that identifies the user. For more information about ARNs and how to use ARNs in
     * policies, see IAM Identifiers in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $arn;

    /**
     * The date and time, in ISO 8601 date-time format, when the user was created.
     *
     * @see http://www.iso.org/iso/iso8601
     */
    private $createDate;

    /**
     * The date and time, in ISO 8601 date-time format, when the user's password was last used to sign in to an AWS website.
     * For a list of AWS websites that capture a user's last sign-in time, see the Credential Reports topic in the *IAM User
     * Guide*. If a password is used more than once in a five-minute span, only the first use is returned in this field. If
     * the field is null (no value), then it indicates that they never signed in with a password. This can be because:.
     *
     * @see http://www.iso.org/iso/iso8601
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/credential-reports.html
     */
    private $passwordLastUsed;

    /**
     * The ARN of the policy used to set the permissions boundary for the user.
     */
    private $permissionsBoundary;

    /**
     * A list of tags that are associated with the specified user. For more information about tagging, see Tagging IAM
     * Identities in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
     */
    private $tags;

    /**
     * @param array{
     *   Path: string,
     *   UserName: string,
     *   UserId: string,
     *   Arn: string,
     *   CreateDate: \DateTimeImmutable,
     *   PasswordLastUsed?: null|\DateTimeImmutable,
     *   PermissionsBoundary?: null|AttachedPermissionsBoundary|array,
     *   Tags?: null|Tag[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->path = $input['Path'] ?? null;
        $this->userName = $input['UserName'] ?? null;
        $this->userId = $input['UserId'] ?? null;
        $this->arn = $input['Arn'] ?? null;
        $this->createDate = $input['CreateDate'] ?? null;
        $this->passwordLastUsed = $input['PasswordLastUsed'] ?? null;
        $this->permissionsBoundary = isset($input['PermissionsBoundary']) ? AttachedPermissionsBoundary::create($input['PermissionsBoundary']) : null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->arn;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getPasswordLastUsed(): ?\DateTimeImmutable
    {
        return $this->passwordLastUsed;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPermissionsBoundary(): ?AttachedPermissionsBoundary
    {
        return $this->permissionsBoundary;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}
