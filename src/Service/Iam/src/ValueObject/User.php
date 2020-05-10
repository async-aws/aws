<?php

namespace AsyncAws\Iam\ValueObject;

final class User
{
    /**
     * The path to the user. For more information about paths, see IAM Identifiers in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $Path;

    /**
     * The friendly name identifying the user.
     */
    private $UserName;

    /**
     * The stable and unique string identifying the user. For more information about IDs, see IAM Identifiers in the *IAM
     * User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $UserId;

    /**
     * The Amazon Resource Name (ARN) that identifies the user. For more information about ARNs and how to use ARNs in
     * policies, see IAM Identifiers in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/Using_Identifiers.html
     */
    private $Arn;

    /**
     * The date and time, in ISO 8601 date-time format, when the user was created.
     *
     * @see http://www.iso.org/iso/iso8601
     */
    private $CreateDate;

    /**
     * The date and time, in ISO 8601 date-time format, when the user's password was last used to sign in to an AWS website.
     * For a list of AWS websites that capture a user's last sign-in time, see the Credential Reports topic in the *IAM User
     * Guide*. If a password is used more than once in a five-minute span, only the first use is returned in this field. If
     * the field is null (no value), then it indicates that they never signed in with a password. This can be because:.
     *
     * @see http://www.iso.org/iso/iso8601
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/credential-reports.html
     */
    private $PasswordLastUsed;

    /**
     * The ARN of the policy used to set the permissions boundary for the user.
     */
    private $PermissionsBoundary;

    /**
     * A list of tags that are associated with the specified user. For more information about tagging, see Tagging IAM
     * Identities in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
     */
    private $Tags;

    /**
     * @param array{
     *   Path: string,
     *   UserName: string,
     *   UserId: string,
     *   Arn: string,
     *   CreateDate: \DateTimeImmutable,
     *   PasswordLastUsed?: null|\DateTimeImmutable,
     *   PermissionsBoundary?: null|\AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary|array,
     *   Tags?: null|\AsyncAws\Iam\ValueObject\Tag[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Path = $input['Path'] ?? null;
        $this->UserName = $input['UserName'] ?? null;
        $this->UserId = $input['UserId'] ?? null;
        $this->Arn = $input['Arn'] ?? null;
        $this->CreateDate = $input['CreateDate'] ?? null;
        $this->PasswordLastUsed = $input['PasswordLastUsed'] ?? null;
        $this->PermissionsBoundary = isset($input['PermissionsBoundary']) ? AttachedPermissionsBoundary::create($input['PermissionsBoundary']) : null;
        $this->Tags = array_map([Tag::class, 'create'], $input['Tags'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->Arn;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->CreateDate;
    }

    public function getPasswordLastUsed(): ?\DateTimeImmutable
    {
        return $this->PasswordLastUsed;
    }

    public function getPath(): string
    {
        return $this->Path;
    }

    public function getPermissionsBoundary(): ?AttachedPermissionsBoundary
    {
        return $this->PermissionsBoundary;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    public function getUserId(): string
    {
        return $this->UserId;
    }

    public function getUserName(): string
    {
        return $this->UserName;
    }
}
