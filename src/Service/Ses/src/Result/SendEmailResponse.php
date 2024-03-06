<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * A unique message ID that you receive when an email is accepted for sending.
 */
class SendEmailResponse extends Result
{
    /**
     * A unique identifier for the message that is generated when the message is accepted.
     *
     * > It's possible for Amazon SES to accept a message without sending it. For example, this can happen when the message
     * > that you're trying to send has an attachment that contains a virus, or when you send a templated email that
     * > contains invalid personalization content.
     *
     * @var string|null
     */
    private $messageId;

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->messageId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->messageId = isset($data['MessageId']) ? (string) $data['MessageId'] : null;
    }
}
