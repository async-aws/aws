<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\ValueObject\ConsumerDescription;

class DescribeStreamConsumerOutput extends Result
{
    /**
     * An object that represents the details of the consumer.
     */
    private $consumerDescription;

    public function getConsumerDescription(): ConsumerDescription
    {
        $this->initialize();

        return $this->consumerDescription;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->consumerDescription = new ConsumerDescription([
            'ConsumerName' => (string) $data['ConsumerDescription']['ConsumerName'],
            'ConsumerARN' => (string) $data['ConsumerDescription']['ConsumerARN'],
            'ConsumerStatus' => (string) $data['ConsumerDescription']['ConsumerStatus'],
            'ConsumerCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['ConsumerDescription']['ConsumerCreationTimestamp'])),
            'StreamARN' => (string) $data['ConsumerDescription']['StreamARN'],
        ]);
    }
}
