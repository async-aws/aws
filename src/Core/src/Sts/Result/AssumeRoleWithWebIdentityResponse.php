<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Sts\ValueObject\AssumedRoleUser;
use AsyncAws\Core\Sts\ValueObject\Credentials;

class AssumeRoleWithWebIdentityResponse extends Result
{
    /**
     * The temporary security credentials, which include an access key ID, a secret access key, and a security token.
     */
    private $Credentials;

    /**
     * The unique user identifier that is returned by the identity provider. This identifier is associated with the
     * `WebIdentityToken` that was submitted with the `AssumeRoleWithWebIdentity` call. The identifier is typically unique
     * to the user and the application that acquired the `WebIdentityToken` (pairwise identifier). For OpenID Connect ID
     * tokens, this field contains the value returned by the identity provider as the token's `sub` (Subject) claim.
     */
    private $SubjectFromWebIdentityToken;

    /**
     * The Amazon Resource Name (ARN) and the assumed role ID, which are identifiers that you can use to refer to the
     * resulting temporary security credentials. For example, you can reference these credentials as a principal in a
     * resource-based policy by using the ARN or assumed role ID. The ARN and ID include the `RoleSessionName` that you
     * specified when you called `AssumeRole`.
     */
    private $AssumedRoleUser;

    /**
     * A percentage value that indicates the packed size of the session policies and session tags combined passed in the
     * request. The request fails if the packed size is greater than 100 percent, which means the policies and tags exceeded
     * the allowed space.
     */
    private $PackedPolicySize;

    /**
     * The issuing authority of the web identity token presented. For OpenID Connect ID tokens, this contains the value of
     * the `iss` field. For OAuth 2.0 access tokens, this contains the value of the `ProviderId` parameter that was passed
     * in the `AssumeRoleWithWebIdentity` request.
     */
    private $Provider;

    /**
     * The intended audience (also known as client ID) of the web identity token. This is traditionally the client
     * identifier issued to the application that requested the web identity token.
     */
    private $Audience;

    public function getAssumedRoleUser(): ?AssumedRoleUser
    {
        $this->initialize();

        return $this->AssumedRoleUser;
    }

    public function getAudience(): ?string
    {
        $this->initialize();

        return $this->Audience;
    }

    public function getCredentials(): ?Credentials
    {
        $this->initialize();

        return $this->Credentials;
    }

    public function getPackedPolicySize(): ?int
    {
        $this->initialize();

        return $this->PackedPolicySize;
    }

    public function getProvider(): ?string
    {
        $this->initialize();

        return $this->Provider;
    }

    public function getSubjectFromWebIdentityToken(): ?string
    {
        $this->initialize();

        return $this->SubjectFromWebIdentityToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->AssumeRoleWithWebIdentityResult;

        $this->Credentials = !$data->Credentials ? null : new Credentials([
            'AccessKeyId' => (string) $data->Credentials->AccessKeyId,
            'SecretAccessKey' => (string) $data->Credentials->SecretAccessKey,
            'SessionToken' => (string) $data->Credentials->SessionToken,
            'Expiration' => new \DateTimeImmutable((string) $data->Credentials->Expiration),
        ]);
        $this->SubjectFromWebIdentityToken = ($v = $data->SubjectFromWebIdentityToken) ? (string) $v : null;
        $this->AssumedRoleUser = !$data->AssumedRoleUser ? null : new AssumedRoleUser([
            'AssumedRoleId' => (string) $data->AssumedRoleUser->AssumedRoleId,
            'Arn' => (string) $data->AssumedRoleUser->Arn,
        ]);
        $this->PackedPolicySize = ($v = $data->PackedPolicySize) ? (int) (string) $v : null;
        $this->Provider = ($v = $data->Provider) ? (string) $v : null;
        $this->Audience = ($v = $data->Audience) ? (string) $v : null;
    }
}
