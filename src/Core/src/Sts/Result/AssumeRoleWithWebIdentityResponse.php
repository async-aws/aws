<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Sts\ValueObject\AssumedRoleUser;
use AsyncAws\Core\Sts\ValueObject\Credentials;
use AsyncAws\Core\Sts\ValueObject\OriginIdentity;
use AsyncAws\Core\Sts\ValueObject\TransactionContext;
use AsyncAws\Core\Sts\ValueObject\TrustedIdentityPropagationContext;

/**
 * Contains the response to a successful AssumeRoleWithWebIdentity request, including temporary Amazon Web Services
 * credentials that can be used to make Amazon Web Services requests.
 */
class AssumeRoleWithWebIdentityResponse extends Result
{
    /**
     * The temporary security credentials, which include an access key ID, a secret access key, and a security token.
     *
     * > The size of the security token that STS API operations return is not fixed. We strongly recommend that you make no
     * > assumptions about the maximum size.
     *
     * @var Credentials|null
     */
    private $credentials;

    /**
     * The unique user identifier that is returned by the identity provider. This identifier is associated with the
     * `WebIdentityToken` that was submitted with the `AssumeRoleWithWebIdentity` call. The identifier is typically unique
     * to the user and the application that acquired the `WebIdentityToken` (pairwise identifier). For OpenID Connect ID
     * tokens, this field contains the value returned by the identity provider as the token's `sub` (Subject) claim.
     *
     * @var string|null
     */
    private $subjectFromWebIdentityToken;

    /**
     * The Amazon Resource Name (ARN) and the assumed role ID, which are identifiers that you can use to refer to the
     * resulting temporary security credentials. For example, you can reference these credentials as a principal in a
     * resource-based policy by using the ARN or assumed role ID. The ARN and ID include the `RoleSessionName` that you
     * specified when you called `AssumeRole`.
     *
     * @var AssumedRoleUser|null
     */
    private $assumedRoleUser;

    /**
     * The percentage (0-100) of the maximum allowed session token size that the returned session token consumes.
     *
     * This field is deprecated. Use `SessionTokenUtilization` instead.
     *
     * @var int|null
     */
    private $packedPolicySize;

    /**
     * The issuing authority of the web identity token presented. For OpenID Connect ID tokens, this contains the value of
     * the `iss` field. For OAuth 2.0 access tokens, this contains the value of the `ProviderId` parameter that was passed
     * in the `AssumeRoleWithWebIdentity` request.
     *
     * @var string|null
     */
    private $provider;

    /**
     * The intended audience (also known as client ID) of the web identity token. This is traditionally the client
     * identifier issued to the application that requested the web identity token.
     *
     * @var string|null
     */
    private $audience;

    /**
     * The value of the source identity that is returned in the JSON web token (JWT) from the identity provider.
     *
     * You can require users to set a source identity value when they assume a role. You do this by using the
     * `sts:SourceIdentity` condition key in a role trust policy. That way, actions that are taken with the role are
     * associated with that user. After the source identity is set, the value cannot be changed. It is present in the
     * request for all actions that are taken by the role and persists across chained role [^1] sessions. You can configure
     * your identity provider to use an attribute associated with your users, like user name or email, as the source
     * identity when calling `AssumeRoleWithWebIdentity`. You do this by adding a claim to the JSON web token. To learn more
     * about OIDC tokens and claims, see Using Tokens with User Pools [^2] in the *Amazon Cognito Developer Guide*. For more
     * information about using source identity, see Monitor and control actions taken with assumed roles [^3] in the *IAM
     * User Guide*.
     *
     * The regex used to validate this parameter is a string of characters consisting of upper- and lower-case alphanumeric
     * characters with no spaces. You can also include underscores or any of the following characters: =,.@-
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html#id_roles_terms-and-concepts
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-using-tokens-with-identity-providers.html
     * [^3]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_control-access_monitor.html
     *
     * @var string|null
     */
    private $sourceIdentity;

    /**
     * @var int|null
     */
    private $sessionTokenUtilization;

    /**
     * @var int|null
     */
    private $sessionTokenSize;

    /**
     * The trusted identity propagation context that Amazon Web Services STS set in the session from the web identity token,
     * through the context provider attached to the OpenID Connect identity provider. It is present only when Amazon Web
     * Services STS set such context in the session. It contains the `aws:OriginIdentity/Subject`,
     * `aws:OriginIdentity/SubjectType`, `aws:OriginIdentity/Issuer` and `aws:OriginIdentity/FederatedProviderArn` keys and
     * the `aws:TransactionContext/IdentityProvider/*` keys that Amazon Web Services STS set in the session. Other keys in
     * the session, such as `aws:IdentityCenterInstanceArn` and `aws:IdentityStoreArn` for an identity provider with an
     * Identity Center instance attached, are not returned.
     *
     * This element contains information about the end user. Do not log it.
     *
     * @var TrustedIdentityPropagationContext|null
     */
    private $trustedIdentityPropagationContext;

    public function getAssumedRoleUser(): ?AssumedRoleUser
    {
        $this->initialize();

        return $this->assumedRoleUser;
    }

    public function getAudience(): ?string
    {
        $this->initialize();

        return $this->audience;
    }

    public function getCredentials(): ?Credentials
    {
        $this->initialize();

        return $this->credentials;
    }

    /**
     * @deprecated
     */
    public function getPackedPolicySize(): ?int
    {
        @trigger_error(\sprintf('The property "packedPolicySize" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->initialize();

        return $this->packedPolicySize;
    }

    public function getProvider(): ?string
    {
        $this->initialize();

        return $this->provider;
    }

    public function getSessionTokenSize(): ?int
    {
        $this->initialize();

        return $this->sessionTokenSize;
    }

    public function getSessionTokenUtilization(): ?int
    {
        $this->initialize();

        return $this->sessionTokenUtilization;
    }

    public function getSourceIdentity(): ?string
    {
        $this->initialize();

        return $this->sourceIdentity;
    }

    public function getSubjectFromWebIdentityToken(): ?string
    {
        $this->initialize();

        return $this->subjectFromWebIdentityToken;
    }

    public function getTrustedIdentityPropagationContext(): ?TrustedIdentityPropagationContext
    {
        $this->initialize();

        return $this->trustedIdentityPropagationContext;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->AssumeRoleWithWebIdentityResult;

        $this->credentials = 0 === $data->Credentials->count() ? null : $this->populateResultCredentials($data->Credentials);
        $this->subjectFromWebIdentityToken = (null !== $v = $data->SubjectFromWebIdentityToken[0]) ? (string) $v : null;
        $this->assumedRoleUser = 0 === $data->AssumedRoleUser->count() ? null : $this->populateResultAssumedRoleUser($data->AssumedRoleUser);
        $this->packedPolicySize = (null !== $v = $data->PackedPolicySize[0]) ? (int) (string) $v : null;
        $this->provider = (null !== $v = $data->Provider[0]) ? (string) $v : null;
        $this->audience = (null !== $v = $data->Audience[0]) ? (string) $v : null;
        $this->sourceIdentity = (null !== $v = $data->SourceIdentity[0]) ? (string) $v : null;
        $this->sessionTokenUtilization = (null !== $v = $data->SessionTokenUtilization[0]) ? (int) (string) $v : null;
        $this->sessionTokenSize = (null !== $v = $data->SessionTokenSize[0]) ? (int) (string) $v : null;
        $this->trustedIdentityPropagationContext = 0 === $data->TrustedIdentityPropagationContext->count() ? null : $this->populateResultTrustedIdentityPropagationContext($data->TrustedIdentityPropagationContext);
    }

    private function populateResultAssumedRoleUser(\SimpleXMLElement $xml): AssumedRoleUser
    {
        return new AssumedRoleUser([
            'AssumedRoleId' => (string) $xml->AssumedRoleId,
            'Arn' => (string) $xml->Arn,
        ]);
    }

    private function populateResultCredentials(\SimpleXMLElement $xml): Credentials
    {
        return new Credentials([
            'AccessKeyId' => (string) $xml->AccessKeyId,
            'SecretAccessKey' => (string) $xml->SecretAccessKey,
            'SessionToken' => (string) $xml->SessionToken,
            'Expiration' => new \DateTimeImmutable((string) $xml->Expiration),
        ]);
    }

    private function populateResultOriginIdentity(\SimpleXMLElement $xml): OriginIdentity
    {
        return new OriginIdentity([
            'Subject' => (null !== $v = $xml->Subject[0]) ? (string) $v : null,
            'SubjectType' => (null !== $v = $xml->SubjectType[0]) ? (string) $v : null,
            'Issuer' => (null !== $v = $xml->Issuer[0]) ? (string) $v : null,
            'FederatedProviderArn' => (null !== $v = $xml->FederatedProviderArn[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultTransactionContext(\SimpleXMLElement $xml): TransactionContext
    {
        return new TransactionContext([
            'IdentityProvider' => (0 === ($v = $xml->IdentityProvider)->count()) ? null : $this->populateResultTransactionContextIdentityProviderMapType($v),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultTransactionContextIdentityProviderMapType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->entry as $item) {
            $a = $item->value;
            $items[$item->key->__toString()] = (string) $a;
        }

        return $items;
    }

    private function populateResultTrustedIdentityPropagationContext(\SimpleXMLElement $xml): TrustedIdentityPropagationContext
    {
        return new TrustedIdentityPropagationContext([
            'OriginIdentity' => 0 === $xml->OriginIdentity->count() ? null : $this->populateResultOriginIdentity($xml->OriginIdentity),
            'TransactionContext' => 0 === $xml->TransactionContext->count() ? null : $this->populateResultTransactionContext($xml->TransactionContext),
        ]);
    }
}
