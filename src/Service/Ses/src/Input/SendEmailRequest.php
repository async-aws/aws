<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;
use AsyncAws\Ses\ValueObject\MessageTag;
use AsyncAws\Ses\ValueObject\Template;

final class SendEmailRequest extends Input
{
    /**
     * The email address that you want to use as the "From" address for the email. The address that you specify has to be
     * verified.
     *
     * @var string|null
     */
    private $FromEmailAddress;

    /**
     * This parameter is used only for sending authorization. It is the ARN of the identity that is associated with the
     * sending authorization policy that permits you to use the email address specified in the `FromEmailAddress` parameter.
     *
     * @var string|null
     */
    private $FromEmailAddressIdentityArn;

    /**
     * An object that contains the recipients of the email message.
     *
     * @var Destination|null
     */
    private $Destination;

    /**
     * The "Reply-to" email addresses for the message. When the recipient replies to the message, each Reply-to address
     * receives the reply.
     *
     * @var string[]|null
     */
    private $ReplyToAddresses;

    /**
     * The address that you want bounce and complaint notifications to be sent to.
     *
     * @var string|null
     */
    private $FeedbackForwardingEmailAddress;

    /**
     * This parameter is used only for sending authorization. It is the ARN of the identity that is associated with the
     * sending authorization policy that permits you to use the email address specified in the
     * `FeedbackForwardingEmailAddress` parameter.
     *
     * @var string|null
     */
    private $FeedbackForwardingEmailAddressIdentityArn;

    /**
     * An object that contains the body of the message. You can send either a Simple message Raw message or a template
     * Message.
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
     * @var MessageTag[]|null
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
     *   FromEmailAddressIdentityArn?: string,
     *   Destination?: \AsyncAws\Ses\ValueObject\Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   FeedbackForwardingEmailAddressIdentityArn?: string,
     *   Content?: \AsyncAws\Ses\ValueObject\EmailContent|array,
     *   EmailTags?: \AsyncAws\Ses\ValueObject\MessageTag[],
     *   ConfigurationSetName?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->FromEmailAddress = $input['FromEmailAddress'] ?? null;
        $this->FromEmailAddressIdentityArn = $input['FromEmailAddressIdentityArn'] ?? null;
        $this->Destination = isset($input['Destination']) ? Destination::create($input['Destination']) : null;
        $this->ReplyToAddresses = $input['ReplyToAddresses'] ?? null;
        $this->FeedbackForwardingEmailAddress = $input['FeedbackForwardingEmailAddress'] ?? null;
        $this->FeedbackForwardingEmailAddressIdentityArn = $input['FeedbackForwardingEmailAddressIdentityArn'] ?? null;
        $this->Content = isset($input['Content']) ? EmailContent::create($input['Content']) : null;
        $this->EmailTags = isset($input['EmailTags']) ? array_map([MessageTag::class, 'create'], $input['EmailTags']) : null;
        $this->ConfigurationSetName = $input['ConfigurationSetName'] ?? null;
        parent::__construct($input);
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

    /**
     * @return MessageTag[]
     */
    public function getEmailTags(): array
    {
        return $this->EmailTags ?? [];
    }

    public function getFeedbackForwardingEmailAddress(): ?string
    {
        return $this->FeedbackForwardingEmailAddress;
    }

    public function getFeedbackForwardingEmailAddressIdentityArn(): ?string
    {
        return $this->FeedbackForwardingEmailAddressIdentityArn;
    }

    public function getFromEmailAddress(): ?string
    {
        return $this->FromEmailAddress;
    }

    public function getFromEmailAddressIdentityArn(): ?string
    {
        return $this->FromEmailAddressIdentityArn;
    }

    /**
     * @return string[]
     */
    public function getReplyToAddresses(): array
    {
        return $this->ReplyToAddresses ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/v2/email/outbound-emails';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
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

    /**
     * @param MessageTag[] $value
     */
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

    public function setFeedbackForwardingEmailAddressIdentityArn(?string $value): self
    {
        $this->FeedbackForwardingEmailAddressIdentityArn = $value;

        return $this;
    }

    public function setFromEmailAddress(?string $value): self
    {
        $this->FromEmailAddress = $value;

        return $this;
    }

    public function setFromEmailAddressIdentityArn(?string $value): self
    {
        $this->FromEmailAddressIdentityArn = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setReplyToAddresses(array $value): self
    {
        $this->ReplyToAddresses = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->FromEmailAddress) {
            $payload['FromEmailAddress'] = $v;
        }
        if (null !== $v = $this->FromEmailAddressIdentityArn) {
            $payload['FromEmailAddressIdentityArn'] = $v;
        }
        if (null !== $v = $this->Destination) {
            $payload['Destination'] = $v->requestBody();
        }
        if (null !== $v = $this->ReplyToAddresses) {
            $index = -1;
            $payload['ReplyToAddresses'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ReplyToAddresses'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->FeedbackForwardingEmailAddress) {
            $payload['FeedbackForwardingEmailAddress'] = $v;
        }
        if (null !== $v = $this->FeedbackForwardingEmailAddressIdentityArn) {
            $payload['FeedbackForwardingEmailAddressIdentityArn'] = $v;
        }
        if (null === $v = $this->Content) {
            throw new InvalidArgument(sprintf('Missing parameter "Content" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Content'] = $v->requestBody();
        if (null !== $v = $this->EmailTags) {
            $index = -1;
            $payload['EmailTags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['EmailTags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->ConfigurationSetName) {
            $payload['ConfigurationSetName'] = $v;
        }

        return $payload;
    }
}
