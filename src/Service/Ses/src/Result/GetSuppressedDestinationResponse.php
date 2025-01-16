<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ses\ValueObject\SuppressedDestination;
use AsyncAws\Ses\ValueObject\SuppressedDestinationAttributes;

/**
 * Information about the suppressed email address.
 */
class GetSuppressedDestinationResponse extends Result
{
    /**
     * An object containing information about the suppressed email address.
     *
     * @var SuppressedDestination
     */
    private $suppressedDestination;

    public function getSuppressedDestination(): SuppressedDestination
    {
        $this->initialize();

        return $this->suppressedDestination;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->suppressedDestination = $this->populateResultSuppressedDestination($data['SuppressedDestination']);
    }

    private function populateResultSuppressedDestination(array $json): SuppressedDestination
    {
        return new SuppressedDestination([
            'EmailAddress' => (string) $json['EmailAddress'],
            'Reason' => (string) $json['Reason'],
            'LastUpdateTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastUpdateTime'])),
            'Attributes' => empty($json['Attributes']) ? null : $this->populateResultSuppressedDestinationAttributes($json['Attributes']),
        ]);
    }

    private function populateResultSuppressedDestinationAttributes(array $json): SuppressedDestinationAttributes
    {
        return new SuppressedDestinationAttributes([
            'MessageId' => isset($json['MessageId']) ? (string) $json['MessageId'] : null,
            'FeedbackId' => isset($json['FeedbackId']) ? (string) $json['FeedbackId'] : null,
        ]);
    }
}
