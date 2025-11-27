<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\ChangeStatus;
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
        $this->changeInfo = $this->populateResultChangeInfo($data->ChangeInfo);
    }

    private function populateResultChangeInfo(\SimpleXMLElement $xml): ChangeInfo
    {
        return new ChangeInfo([
            'Id' => (string) $xml->Id,
            'Status' => !ChangeStatus::exists((string) $xml->Status) ? ChangeStatus::UNKNOWN_TO_SDK : (string) $xml->Status,
            'SubmittedAt' => new \DateTimeImmutable((string) $xml->SubmittedAt),
            'Comment' => (null !== $v = $xml->Comment[0]) ? (string) $v : null,
        ]);
    }
}
