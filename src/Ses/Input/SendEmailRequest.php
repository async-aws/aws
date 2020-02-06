<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class SendEmailRequest
{
    /**
     * The email address that you want to use as the "From" address for the email. The address that you specify has to be
     * verified.
     *
     * @var string|null
     */
    private $FromEmailAddress;

    /**
     * An object that contains the recipients of the email message.
     *
     * @required
     *
     * @var Destination|null
     */
    private $Destination;

    /**
     * The "Reply-to" email addresses for the message. When the recipient replies to the message, each Reply-to address
     * receives the reply.
     *
     * @var string[]
     */
    private $ReplyToAddresses;

    /**
     * The address that you want bounce and complaint notifications to be sent to.
     *
     * @var string|null
     */
    private $FeedbackForwardingEmailAddress;

    /**
     * An object that contains the body of the message. You can send either a Simple message or a Raw message.
     *
     * @required
     *
     * @var EmailContent|null
     */
    private $Content;

    /**
     * A list of tags, in the form of name/value pairs, to apply to an email that you send using the `SendEmail` operation.
     * Tags correspond to characteristics of the email that you define, so that you can publish email sending events.
     *
     * @var MessageTag[]
     */
    private $EmailTags;

    /**
     * The name of the configuration set that you want to use when sending the email.
     *
     * @var string|null
     */
    private $ConfigurationSetName;

    /**
     * @param array{
     *   FromEmailAddress?: string,
     *   Destination: \AsyncAws\Ses\Input\Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   Content: \AsyncAws\Ses\Input\EmailContent|array,
     *   EmailTags?: \AsyncAws\Ses\Input\MessageTag[],
     *   ConfigurationSetName?: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->FromEmailAddress = $input['FromEmailAddress'] ?? null;
        $this->Destination = isset($input['Destination']) ? Destination::create($input['Destination']) : null;
        $this->ReplyToAddresses = $input['ReplyToAddresses'] ?? [];
        $this->FeedbackForwardingEmailAddress = $input['FeedbackForwardingEmailAddress'] ?? null;
        $this->Content = isset($input['Content']) ? EmailContent::create($input['Content']) : null;
        $this->EmailTags = array_map(function ($item) { return MessageTag::create($item); }, $input['EmailTags'] ?? []);
        $this->ConfigurationSetName = $input['ConfigurationSetName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfigurationSetName(): ?string
    {
        return $this->ConfigurationSetName;
    }

    public function getContent(): ?EmailContent
    {
        return $this->Content;
    }

    public function getDestination(): ?Destination
    {
        return $this->Destination;
    }

    public function getEmailTags(): array
    {
        return $this->EmailTags;
    }

    public function getFeedbackForwardingEmailAddress(): ?string
    {
        return $this->FeedbackForwardingEmailAddress;
    }

    public function getFromEmailAddress(): ?string
    {
        return $this->FromEmailAddress;
    }

    public function getReplyToAddresses(): array
    {
        return $this->ReplyToAddresses;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'SendEmail', 'Version' => '2019-09-27'];
        if (null !== $this->FromEmailAddress) {
            $payload['FromEmailAddress'] = $this->FromEmailAddress;
        }
        if (null !== $this->Destination) {
            $payload['Destination'] = $this->Destination;
        }
        if (null !== $this->ReplyToAddresses) {
            $payload['ReplyToAddresses'] = $this->ReplyToAddresses;
        }
        if (null !== $this->FeedbackForwardingEmailAddress) {
            $payload['FeedbackForwardingEmailAddress'] = $this->FeedbackForwardingEmailAddress;
        }
        if (null !== $this->Content) {
            $payload['Content'] = $this->Content;
        }
        if (null !== $this->EmailTags) {
            $payload['EmailTags'] = $this->EmailTags;
        }
        if (null !== $this->ConfigurationSetName) {
            $payload['ConfigurationSetName'] = $this->ConfigurationSetName;
        }

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/v2/email/outbound-emails';
    }

    public function setConfigurationSetName(?string $value): self
    {
        $this->ConfigurationSetName = $value;

        return $this;
    }

    public function setContent(?EmailContent $value): self
    {
        $this->Content = $value;

        return $this;
    }

    public function setDestination(?Destination $value): self
    {
        $this->Destination = $value;

        return $this;
    }

    public function setEmailTags(array $value): self
    {
        $this->EmailTags = $value;

        return $this;
    }

    public function setFeedbackForwardingEmailAddress(?string $value): self
    {
        $this->FeedbackForwardingEmailAddress = $value;

        return $this;
    }

    public function setFromEmailAddress(?string $value): self
    {
        $this->FromEmailAddress = $value;

        return $this;
    }

    public function setReplyToAddresses(array $value): self
    {
        $this->ReplyToAddresses = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Destination', 'Content'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        if ($this->Destination) {
            $this->Destination->validate();
        }
        if ($this->Content) {
            $this->Content->validate();
        }
        foreach ($this->EmailTags as $item) {
            $item->validate();
        }
    }
}
