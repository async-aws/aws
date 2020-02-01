<?php

namespace AsyncAws\Sqs\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait SendMessageResultTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        $data = $data->SendMessageResult;
        $this->MD5OfMessageBody = $this->xmlValueOrNull($data->MD5OfMessageBody);
        $this->MD5OfMessageAttributes = $this->xmlValueOrNull($data->MD5OfMessageAttributes);
        $this->MD5OfMessageSystemAttributes = $this->xmlValueOrNull($data->MD5OfMessageSystemAttributes);
        $this->MessageId = $this->xmlValueOrNull($data->MessageId);
        $this->SequenceNumber = $this->xmlValueOrNull($data->SequenceNumber);
    }
}
