<?php

namespace AsyncAws\Core\Sts\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType;
use AsyncAws\Core\Sts\ValueObject\Tag;

final class AssumeRoleRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the role to assume.
     *
     * @required
     *
     * @var string|null
     */
    private $RoleArn;

    /**
     * An identifier for the assumed role session.
     *
     * @required
     *
     * @var string|null
     */
    private $RoleSessionName;

    /**
     * The Amazon Resource Names (ARNs) of the IAM managed policies that you want to use as managed session policies. The
     * policies must exist in the same account as the role.
     *
     * @var PolicyDescriptorType[]
     */
    private $PolicyArns;

    /**
     * An IAM policy in JSON format that you want to use as an inline session policy.
     *
     * @var string|null
     */
    private $Policy;

    /**
     * The duration, in seconds, of the role session. The value can range from 900 seconds (15 minutes) up to the maximum
     * session duration setting for the role. This setting can have a value from 1 hour to 12 hours. If you specify a value
     * higher than this setting, the operation fails. For example, if you specify a session duration of 12 hours, but your
     * administrator set the maximum session duration to 6 hours, your operation fails. To learn how to view the maximum
     * value for your role, see View the Maximum Session Duration Setting for a Role in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles_use.html#id_roles_use_view-role-max-session
     *
     * @var int|null
     */
    private $DurationSeconds;

    /**
     * A list of session tags that you want to pass. Each session tag consists of a key name and an associated value. For
     * more information about session tags, see Tagging AWS STS Sessions in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_session-tags.html
     *
     * @var Tag[]
     */
    private $Tags;

    /**
     * A list of keys for session tags that you want to set as transitive. If you set a tag key as transitive, the
     * corresponding key and value passes to subsequent sessions in a role chain. For more information, see Chaining Roles
     * with Session Tags in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_session-tags.html#id_session-tags_role-chaining
     *
     * @var string[]
     */
    private $TransitiveTagKeys;

    /**
     * A unique identifier that might be required when you assume a role in another account. If the administrator of the
     * account to which the role belongs provided you with an external ID, then provide that value in the `ExternalId`
     * parameter. This value can be any string, such as a passphrase or account number. A cross-account role is usually set
     * up to trust everyone in an account. Therefore, the administrator of the trusting account might send an external ID to
     * the administrator of the trusted account. That way, only someone with the ID can assume the role, rather than
     * everyone in the account. For more information about the external ID, see How to Use an External ID When Granting
     * Access to Your AWS Resources to a Third Party in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles_create_for-user_externalid.html
     *
     * @var string|null
     */
    private $ExternalId;

    /**
     * The identification number of the MFA device that is associated with the user who is making the `AssumeRole` call.
     * Specify this value if the trust policy of the role being assumed includes a condition that requires MFA
     * authentication. The value is either the serial number for a hardware device (such as `GAHT12345678`) or an Amazon
     * Resource Name (ARN) for a virtual device (such as `arn:aws:iam::123456789012:mfa/user`).
     *
     * @var string|null
     */
    private $SerialNumber;

    /**
     * The value provided by the MFA device, if the trust policy of the role being assumed requires MFA (that is, if the
     * policy includes a condition that tests for MFA). If the role being assumed requires MFA and if the `TokenCode` value
     * is missing or expired, the `AssumeRole` call returns an "access denied" error.
     *
     * @var string|null
     */
    private $TokenCode;

    /**
     * @param array{
     *   RoleArn?: string,
     *   RoleSessionName?: string,
     *   PolicyArns?: \AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     *   Tags?: \AsyncAws\Core\Sts\ValueObject\Tag[],
     *   TransitiveTagKeys?: string[],
     *   ExternalId?: string,
     *   SerialNumber?: string,
     *   TokenCode?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->RoleArn = $input['RoleArn'] ?? null;
        $this->RoleSessionName = $input['RoleSessionName'] ?? null;
        $this->PolicyArns = array_map([PolicyDescriptorType::class, 'create'], $input['PolicyArns'] ?? []);
        $this->Policy = $input['Policy'] ?? null;
        $this->DurationSeconds = $input['DurationSeconds'] ?? null;
        $this->Tags = array_map([Tag::class, 'create'], $input['Tags'] ?? []);
        $this->TransitiveTagKeys = $input['TransitiveTagKeys'] ?? [];
        $this->ExternalId = $input['ExternalId'] ?? null;
        $this->SerialNumber = $input['SerialNumber'] ?? null;
        $this->TokenCode = $input['TokenCode'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDurationSeconds(): ?int
    {
        return $this->DurationSeconds;
    }

    public function getExternalId(): ?string
    {
        return $this->ExternalId;
    }

    public function getPolicy(): ?string
    {
        return $this->Policy;
    }

    /**
     * @return PolicyDescriptorType[]
     */
    public function getPolicyArns(): array
    {
        return $this->PolicyArns;
    }

    public function getRoleArn(): ?string
    {
        return $this->RoleArn;
    }

    public function getRoleSessionName(): ?string
    {
        return $this->RoleSessionName;
    }

    public function getSerialNumber(): ?string
    {
        return $this->SerialNumber;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    public function getTokenCode(): ?string
    {
        return $this->TokenCode;
    }

    /**
     * @return string[]
     */
    public function getTransitiveTagKeys(): array
    {
        return $this->TransitiveTagKeys;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'AssumeRole', 'Version' => '2011-06-15'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDurationSeconds(?int $value): self
    {
        $this->DurationSeconds = $value;

        return $this;
    }

    public function setExternalId(?string $value): self
    {
        $this->ExternalId = $value;

        return $this;
    }

    public function setPolicy(?string $value): self
    {
        $this->Policy = $value;

        return $this;
    }

    /**
     * @param PolicyDescriptorType[] $value
     */
    public function setPolicyArns(array $value): self
    {
        $this->PolicyArns = $value;

        return $this;
    }

    public function setRoleArn(?string $value): self
    {
        $this->RoleArn = $value;

        return $this;
    }

    public function setRoleSessionName(?string $value): self
    {
        $this->RoleSessionName = $value;

        return $this;
    }

    public function setSerialNumber(?string $value): self
    {
        $this->SerialNumber = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    public function setTokenCode(?string $value): self
    {
        $this->TokenCode = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setTransitiveTagKeys(array $value): self
    {
        $this->TransitiveTagKeys = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->RoleArn) {
            throw new InvalidArgument(sprintf('Missing parameter "RoleArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RoleArn'] = $v;
        if (null === $v = $this->RoleSessionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RoleSessionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RoleSessionName'] = $v;

        $index = 0;
        foreach ($this->PolicyArns as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["PolicyArns.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        if (null !== $v = $this->Policy) {
            $payload['Policy'] = $v;
        }
        if (null !== $v = $this->DurationSeconds) {
            $payload['DurationSeconds'] = $v;
        }

        $index = 0;
        foreach ($this->Tags as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Tags.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        $index = 0;
        foreach ($this->TransitiveTagKeys as $mapValue) {
            ++$index;
            $payload["TransitiveTagKeys.member.$index"] = $mapValue;
        }

        if (null !== $v = $this->ExternalId) {
            $payload['ExternalId'] = $v;
        }
        if (null !== $v = $this->SerialNumber) {
            $payload['SerialNumber'] = $v;
        }
        if (null !== $v = $this->TokenCode) {
            $payload['TokenCode'] = $v;
        }

        return $payload;
    }
}
