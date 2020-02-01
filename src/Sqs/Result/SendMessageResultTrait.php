<?php

namespace AsyncAws\Sqs\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait SendMessageResultTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $data = $data->SendMessageResult;
        $this->MD5OfMessageBody = $data->MD5OfMessageBody;
        $this->MD5OfMessageAttributes = $data->MD5OfMessageAttributes;
        $this->MD5OfMessageSystemAttributes = $data->MD5OfMessageSystemAttributes;
        $this->MessageId = $data->MessageId;
        $this->SequenceNumber = $data->SequenceNumber;
    }
}
