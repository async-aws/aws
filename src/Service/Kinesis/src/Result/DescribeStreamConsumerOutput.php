<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\ValueObject\ConsumerDescription;

class DescribeStreamConsumerOutput extends Result
{
    /**
     * An object that represents the details of the consumer.
     *
     * @var ConsumerDescription
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

        $this->consumerDescription = $this->populateResultConsumerDescription($data['ConsumerDescription']);
    }

    private function populateResultConsumerDescription(array $json): ConsumerDescription
    {
        return new ConsumerDescription([
            'ConsumerName' => (string) $json['ConsumerName'],
            'ConsumerARN' => (string) $json['ConsumerARN'],
            'ConsumerStatus' => (string) $json['ConsumerStatus'],
            'ConsumerCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['ConsumerCreationTimestamp'])),
            'StreamARN' => (string) $json['StreamARN'],
        ]);
    }
}
