<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\ValueObject\ChangeInfo;

/**
 * A complex type that contains the response to a `DeleteHostedZone` request.
 */
class DeleteHostedZoneResponse extends Result
{
    /**
     * A complex type that contains the ID, the status, and the date and time of a request to delete a hosted zone.
     *
     * @var ChangeInfo
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
