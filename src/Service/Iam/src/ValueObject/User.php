<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about an IAM user entity.
 *
 * This data type is used as a response element in the following operations:
 *
 * - CreateUser [^1]
 * - GetUser [^2]
 * - ListUsers [^3]
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateUser.html
 * [^2]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_GetUser.html
 * [^3]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListUsers.html
 */
final class User
{
    /**
     * The path to the user. For more information about paths, see IAM identifiers [^1] in the *IAM User Guide*.
     *
     * The ARN of the policy used to set the permissions boundary for the user.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     *
     * @var string
     */
    private $path;

    /**
     * The friendly name identifying the user.
     *
     * @var string
     */
    private $userName;

    /**
     * The stable and unique string identifying the user. For more information about IDs, see IAM identifiers [^1] in the
     * *IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     *
     * @var string
     */
    private $userId;

    /**
     * The Amazon Resource Name (ARN) that identifies the user. For more information about ARNs and how to use ARNs in
     * policies, see IAM Identifiers [^1] in the *IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     *
     * @var string
     */
    private $arn;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the user was created.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     *
     * @var \DateTimeImmutable
     */
    private $createDate;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the user's password was last used to sign in to an Amazon
     * Web Services website. For a list of Amazon Web Services websites that capture a user's last sign-in time, see the
     * Credential reports [^2] topic in the *IAM User Guide*. If a password is used more than once in a five-minute span,
     * only the first use is returned in this field. If the field is null (no value), then it indicates that they never
     * signed in with a password. This can be because:
     *
     * - The user never had a password.
     * - A password exists but has not been used since IAM started tracking this information on October 20, 2014.
     *
     * A null value does not mean that the user *never* had a password. Also, if the user does not currently have a password
     * but had one in the past, then this field contains the date and time the most recent password was used.
     *
     * This value is returned only in the GetUser [^3] and ListUsers [^4] operations.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/credential-reports.html
     * [^3]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_GetUser.html
     * [^4]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListUsers.html
     *
     * @var \DateTimeImmutable|null
     */
    private $passwordLastUsed;

    /**
     * For more information about permissions boundaries, see Permissions boundaries for IAM identities [^1] in the *IAM
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies_boundaries.html
     *
     * @var AttachedPermissionsBoundary|null
     */
    private $permissionsBoundary;

    /**
     * A list of tags that are associated with the user. For more information about tagging, see Tagging IAM resources [^1]
     * in the *IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
     *
     * @var Tag[]|null
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
     *   Tags?: null|array<Tag|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->path = $input['Path'] ?? $this->throwException(new InvalidArgument('Missing required field "Path".'));
        $this->userName = $input['UserName'] ?? $this->throwException(new InvalidArgument('Missing required field "UserName".'));
        $this->userId = $input['UserId'] ?? $this->throwException(new InvalidArgument('Missing required field "UserId".'));
        $this->arn = $input['Arn'] ?? $this->throwException(new InvalidArgument('Missing required field "Arn".'));
        $this->createDate = $input['CreateDate'] ?? $this->throwException(new InvalidArgument('Missing required field "CreateDate".'));
        $this->passwordLastUsed = $input['PasswordLastUsed'] ?? null;
        $this->permissionsBoundary = isset($input['PermissionsBoundary']) ? AttachedPermissionsBoundary::create($input['PermissionsBoundary']) : null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
    }

    /**
     * @param array{
     *   Path: string,
     *   UserName: string,
     *   UserId: string,
     *   Arn: string,
     *   CreateDate: \DateTimeImmutable,
     *   PasswordLastUsed?: null|\DateTimeImmutable,
     *   PermissionsBoundary?: null|AttachedPermissionsBoundary|array,
     *   Tags?: null|array<Tag|array>,
     * }|User $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
