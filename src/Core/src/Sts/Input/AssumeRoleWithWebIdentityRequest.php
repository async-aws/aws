<?php

namespace AsyncAws\Core\Sts\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class AssumeRoleWithWebIdentityRequest
{
    /**
     * The Amazon Resource Name (ARN) of the role that the caller is assuming.
     *
     * @required
     *
     * @var string|null
     */
    private $RoleArn;

    /**
     * An identifier for the assumed role session. Typically, you pass the name or identifier that is associated with the
     * user who is using your application. That way, the temporary security credentials that your application will use are
     * associated with that user. This session name is included as part of the ARN and assumed role ID in the
     * `AssumedRoleUser` response element.
     *
     * @required
     *
     * @var string|null
     */
    private $RoleSessionName;

    /**
     * The OAuth 2.0 access token or OpenID Connect ID token that is provided by the identity provider. Your application
     * must get this token by authenticating the user who is using your application with a web identity provider before the
     * application makes an `AssumeRoleWithWebIdentity` call.
     *
     * @required
     *
     * @var string|null
     */
    private $WebIdentityToken;

    /**
     * The fully qualified host component of the domain name of the identity provider.
     *
     * @var string|null
     */
    private $ProviderId;

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
     * @param array{
     *   RoleArn?: string,
     *   RoleSessionName?: string,
     *   WebIdentityToken?: string,
     *   ProviderId?: string,
     *   PolicyArns?: \AsyncAws\Core\Sts\Input\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->RoleArn = $input['RoleArn'] ?? null;
        $this->RoleSessionName = $input['RoleSessionName'] ?? null;
        $this->WebIdentityToken = $input['WebIdentityToken'] ?? null;
        $this->ProviderId = $input['ProviderId'] ?? null;
        $this->PolicyArns = array_map(function ($item) { return PolicyDescriptorType::create($item); }, $input['PolicyArns'] ?? []);
        $this->Policy = $input['Policy'] ?? null;
        $this->DurationSeconds = $input['DurationSeconds'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDurationSeconds(): ?int
    {
        return $this->DurationSeconds;
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

    public function getProviderId(): ?string
    {
        return $this->ProviderId;
    }

    public function getRoleArn(): ?string
    {
        return $this->RoleArn;
    }

    public function getRoleSessionName(): ?string
    {
        return $this->RoleSessionName;
    }

    public function getWebIdentityToken(): ?string
    {
        return $this->WebIdentityToken;
    }

    /**
     * @internal
     */
    public function requestBody(): string
    {
        $payload = ['Action' => 'AssumeRoleWithWebIdentity', 'Version' => '2011-06-15'];
        $indices = new \stdClass();
        $payload['RoleArn'] = $this->RoleArn;
        $payload['RoleSessionName'] = $this->RoleSessionName;
        $payload['WebIdentityToken'] = $this->WebIdentityToken;
        if (null !== $v = $this->ProviderId) {
            $payload['ProviderId'] = $v;
        }

        (static function (array $input) use (&$payload, $indices) {
            $indices->kfc822c1 = 0;
            foreach ($input as $value) {
                ++$indices->kfc822c1;

                if (null !== $value) {
                    (static function (PolicyDescriptorType $input) use (&$payload, $indices) {
                        if (null !== $v = $input->getarn()) {
                            $payload["PolicyArns.member.{$indices->kfc822c1}.arn"] = $v;
                        }
                    })($value);
                }
            }
        })($this->PolicyArns);
        if (null !== $v = $this->Policy) {
            $payload['Policy'] = $v;
        }
        if (null !== $v = $this->DurationSeconds) {
            $payload['DurationSeconds'] = $v;
        }

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }

    /**
     * @internal
     */
    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        return $headers;
    }

    /**
     * @internal
     */
    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    /**
     * @internal
     */
    public function requestUri(): string
    {
        return '/';
    }

    public function setDurationSeconds(?int $value): self
    {
        $this->DurationSeconds = $value;

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

    public function setProviderId(?string $value): self
    {
        $this->ProviderId = $value;

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

    public function setWebIdentityToken(?string $value): self
    {
        $this->WebIdentityToken = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->RoleArn) {
            throw new InvalidArgument(sprintf('Missing parameter "RoleArn" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->RoleSessionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RoleSessionName" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->WebIdentityToken) {
            throw new InvalidArgument(sprintf('Missing parameter "WebIdentityToken" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        foreach ($this->PolicyArns as $item) {
            $item->validate();
        }
    }
}
