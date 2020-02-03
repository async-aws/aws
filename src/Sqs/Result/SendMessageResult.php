<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SendMessageResult extends Result
{
    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $MD5OfMessageBody;

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $MD5OfMessageAttributes;

    /**
     * An MD5 digest of the non-URL-encoded message system attribute string. You can use this attribute to verify that
     * Amazon SQS received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest.
     */
    private $MD5OfMessageSystemAttributes;

    /**
     * An attribute containing the `MessageId` of the message sent to the queue. For more information, see Queue and Message
     * Identifiers in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-message-identifiers.html
     */
    private $MessageId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     */
    private $SequenceNumber;

    public function getMD5OfMessageAttributes(): ?string
    {
        $this->initialize();

        return $this->MD5OfMessageAttributes;
    }

    public function getMD5OfMessageBody(): ?string
    {
        $this->initialize();

        return $this->MD5OfMessageBody;
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
}
