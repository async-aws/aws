<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetCallerIdentityResponse extends Result
{
    /**
     * The unique identifier of the calling entity. The exact value depends on the type of entity that is making the call.
     * The values returned are those listed in the **aws:userid** column in the Principal table found on the **Policy
     * Variables** reference page in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_policies_variables.html#principaltable
     */
    private $UserId;

    /**
     * The AWS account ID number of the account that owns or contains the calling entity.
     */
    private $Account;

    /**
     * The AWS ARN associated with the calling entity.
     */
    private $Arn;

    public function getAccount(): ?string
    {
        $this->initialize();

        return $this->Account;
    }

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->Arn;
    }

    public function getUserId(): ?string
    {
        $this->initialize();

        return $this->UserId;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->GetCallerIdentityResult;

        $this->UserId = ($v = $data->UserId) ? (string) $v : null;
        $this->Account = ($v = $data->Account) ? (string) $v : null;
        $this->Arn = ($v = $data->Arn) ? (string) $v : null;
    }
}
