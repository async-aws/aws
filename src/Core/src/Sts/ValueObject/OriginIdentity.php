<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * The session's `aws:OriginIdentity/Subject`, `aws:OriginIdentity/SubjectType`, `aws:OriginIdentity/Issuer` and
 * `aws:OriginIdentity/FederatedProviderArn` keys. A member is absent when the session does not have the key.
 */
final class OriginIdentity
{
    /**
     * The value of the `aws:OriginIdentity/Subject` key: the end user's identifier, taken from the token claim that the
     * context provider maps as the subject.
     *
     * @var string|null
     */
    private $subject;

    /**
     * The value of the `aws:OriginIdentity/SubjectType` key, which indicates whether the end user is a person (`user`) or a
     * non-human caller such as an application or service (`workload`).
     *
     * @var string|null
     */
    private $subjectType;

    /**
     * The value of the `aws:OriginIdentity/Issuer` key. For `AssumeRoleWithWebIdentity`, this is the `iss` claim of the
     * token without the `https://` prefix. For `AssumeRoleWithSAML`, this is a hash of the key that signed the SAML
     * assertion, not the `Issuer` element of the assertion.
     *
     * @var string|null
     */
    private $issuer;

    /**
     * The value of the `aws:OriginIdentity/FederatedProviderArn` key: the ARN of the IAM identity provider that the request
     * federated through.
     *
     * @var string|null
     */
    private $federatedProviderArn;

    /**
     * @param array{
     *   Subject?: string|null,
     *   SubjectType?: string|null,
     *   Issuer?: string|null,
     *   FederatedProviderArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subject = $input['Subject'] ?? null;
        $this->subjectType = $input['SubjectType'] ?? null;
        $this->issuer = $input['Issuer'] ?? null;
        $this->federatedProviderArn = $input['FederatedProviderArn'] ?? null;
    }

    /**
     * @param array{
     *   Subject?: string|null,
     *   SubjectType?: string|null,
     *   Issuer?: string|null,
     *   FederatedProviderArn?: string|null,
     * }|OriginIdentity $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFederatedProviderArn(): ?string
    {
        return $this->federatedProviderArn;
    }

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getSubjectType(): ?string
    {
        return $this->subjectType;
    }
}
