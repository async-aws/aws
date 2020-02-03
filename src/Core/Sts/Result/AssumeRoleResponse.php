<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AssumeRoleResponse extends Result
{
    /**
     * The temporary security credentials, which include an access key ID, a secret access key, and a security (or session)
     * token.
     */
    private $Credentials;

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

    public function getAssumedRoleUser(): ?AssumedRoleUser
    {
        $this->initialize();

        return $this->AssumedRoleUser;
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

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->AssumeRoleResult;

        $this->Credentials = new Credentials([
            'AccessKeyId' => $this->xmlValueOrNull($data->AccessKeyId, 'string'),
            'SecretAccessKey' => $this->xmlValueOrNull($data->SecretAccessKey, 'string'),
            'SessionToken' => $this->xmlValueOrNull($data->SessionToken, 'string'),
            'Expiration' => $this->xmlValueOrNull($data->Expiration, '\DateTimeImmutable'),
        ]);
        $this->AssumedRoleUser = new AssumedRoleUser([
            'AssumedRoleId' => $this->xmlValueOrNull($data->AssumedRoleId, 'string'),
            'Arn' => $this->xmlValueOrNull($data->Arn, 'string'),
        ]);
        $this->PackedPolicySize = $this->xmlValueOrNull($data->PackedPolicySize, 'int');
    }
}
