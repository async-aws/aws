<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SendMessageResult extends Result
{
    private $MD5OfMessageBody;

    private $MD5OfMessageAttributes;

    private $MD5OfMessageSystemAttributes;

    private $MessageId;

    private $SequenceNumber;

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->SendMessageResult;

        $this->MD5OfMessageBody = $this->xmlValueOrNull($data->MD5OfMessageBody, 'string');
        $this->MD5OfMessageAttributes = $this->xmlValueOrNull($data->MD5OfMessageAttributes, 'string');
        $this->MD5OfMessageSystemAttributes = $this->xmlValueOrNull($data->MD5OfMessageSystemAttributes, 'string');
        $this->MessageId = $this->xmlValueOrNull($data->MessageId, 'string');
        $this->SequenceNumber = $this->xmlValueOrNull($data->SequenceNumber, 'string');
    }

    public function getMD5OfMessageBody(): ?string
    {
        $this->initialize();

        return $this->MD5OfMessageBody;
    }

    public function getMD5OfMessageAttributes(): ?string
    {
        $this->initialize();

        return $this->MD5OfMessageAttributes;
    }

    public function getMD5OfMessageSystemAttributes(): ?string
    {
        $this->initialize();

        return $this->MD5OfMessageSystemAttributes;
    }

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    public function getSequenceNumber(): ?string
    {
        $this->initialize();

        return $this->SequenceNumber;
    }
}
