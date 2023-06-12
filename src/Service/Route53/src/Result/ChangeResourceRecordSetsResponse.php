<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\ValueObject\ChangeInfo;

/**
 * A complex type containing the response for the request.
 */
class ChangeResourceRecordSetsResponse extends Result
{
    /**
     * A complex type that contains information about changes made to your hosted zone.
     *
     * This element contains an ID that you use when performing a GetChange [^1] action to get detailed information about
     * the change.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetChange.html
     */
    private $changeInfo;

    public function getChangeInfo(): ChangeInfo
    {
        $this->initialize();

        return $this->changeInfo;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->changeInfo = new ChangeInfo([
            'Id' => (string) $data->ChangeInfo->Id,
            'Status' => (string) $data->ChangeInfo->Status,
            'SubmittedAt' => new \DateTimeImmutable((string) $data->ChangeInfo->SubmittedAt),
            'Comment' => ($v = $data->ChangeInfo->Comment) ? (string) $v : null,
        ]);
    }
}
