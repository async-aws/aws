<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;

class SendMessageResult extends Result
{
    use SendMessageResultTrait;

    private $MD5OfMessageBody;

    private $MD5OfMessageAttributes;

    private $MD5OfMessageSystemAttributes;

    private $MessageId;

    private $SequenceNumber;

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
