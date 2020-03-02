<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetCallerIdentityResponse extends Result
{
    private $UserId;

    private $Account;

    private $Arn;

    /**
     * The AWS account ID number of the account that owns or contains the calling entity.
     */
    public function getAccount(): ?string
    {
        $this->initialize();

        return $this->Account;
    }

    /**
     * The AWS ARN associated with the calling entity.
     */
    public function getArn(): ?string
    {
        $this->initialize();

        return $this->Arn;
    }

    /**
     * The unique identifier of the calling entity. The exact value depends on the type of entity that is making the call.
     * The values returned are those listed in the **aws:userid** column in the Principal table found on the **Policy
     * Variables** reference page in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_policies_variables.html#principaltable
     */
    public function getUserId(): ?string
    {
        $this->initialize();

        return $this->UserId;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->GetCallerIdentityResult;

        $this->UserId = ($v = $data->UserId) ? (string) $v : null;
        $this->Account = ($v = $data->Account) ? (string) $v : null;
        $this->Arn = ($v = $data->Arn) ? (string) $v : null;
    }
}
