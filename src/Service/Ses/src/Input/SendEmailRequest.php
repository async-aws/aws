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
     *   Destination?: \AsyncAws\Ses\Input\Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   Content?: \AsyncAws\Ses\Input\EmailContent|array,
     *   EmailTags?: \AsyncAws\Ses\Input\MessageTag[],
     *   ConfigurationSetName?: string,
     * } $input
     */
    public function __construct(array $input = [])
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

    /**
     * @return MessageTag[]
     */
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

    /**
     * @return string[]
     */
    public function getReplyToAddresses(): array
    {
        return $this->ReplyToAddresses;
    }

    public function requestBody(): string
    {
        $payload = [];
        $indices = new \stdClass();
        if (null !== $v = $this->FromEmailAddress) {
            $payload['FromEmailAddress'] = $v;
        }

        if (null !== $this->Destination) {
            (static function (Destination $input) use (&$payload, $indices) {
                (static function (array $input) use (&$payload, $indices) {
                    $indices->kbe21867 = -1;
                    foreach ($input as $value) {
                        ++$indices->kbe21867;
                        $payload['Destination']['ToAddresses'][$indices->kbe21867] = $value;
                    }
                })($input->getToAddresses());

                (static function (array $input) use (&$payload, $indices) {
                    $indices->k385be6f = -1;
                    foreach ($input as $value) {
                        ++$indices->k385be6f;
                        $payload['Destination']['CcAddresses'][$indices->k385be6f] = $value;
                    }
                })($input->getCcAddresses());

                (static function (array $input) use (&$payload, $indices) {
                    $indices->k4191b78 = -1;
                    foreach ($input as $value) {
                        ++$indices->k4191b78;
                        $payload['Destination']['BccAddresses'][$indices->k4191b78] = $value;
                    }
                })($input->getBccAddresses());
            })($this->Destination);
        }

        (static function (array $input) use (&$payload, $indices) {
            $indices->k6284657 = -1;
            foreach ($input as $value) {
                ++$indices->k6284657;
                $payload['ReplyToAddresses'][$indices->k6284657] = $value;
            }
        })($this->ReplyToAddresses);
        if (null !== $v = $this->FeedbackForwardingEmailAddress) {
            $payload['FeedbackForwardingEmailAddress'] = $v;
        }

        if (null !== $this->Content) {
            (static function (EmailContent $input) use (&$payload) {
                if (null !== $v = $input->getSimple()) {
                    (static function (Message $input) use (&$payload) {
                        if (null !== $input->getSubject()) {
                            (static function (Content $input) use (&$payload) {
                                $payload['Content']['Simple']['Subject']['Data'] = $input->getData();

                                if (null !== $v = $input->getCharset()) {
                                    $payload['Content']['Simple']['Subject']['Charset'] = $v;
                                }
                            })($input->getSubject());
                        }

                        if (null !== $input->getBody()) {
                            (static function (Body $input) use (&$payload) {
                                if (null !== $v = $input->getText()) {
                                    (static function (Content $input) use (&$payload) {
                                        $payload['Content']['Simple']['Body']['Text']['Data'] = $input->getData();

                                        if (null !== $v = $input->getCharset()) {
                                            $payload['Content']['Simple']['Body']['Text']['Charset'] = $v;
                                        }
                                    })($v);
                                }

                                if (null !== $v = $input->getHtml()) {
                                    (static function (Content $input) use (&$payload) {
                                        $payload['Content']['Simple']['Body']['Html']['Data'] = $input->getData();

                                        if (null !== $v = $input->getCharset()) {
                                            $payload['Content']['Simple']['Body']['Html']['Charset'] = $v;
                                        }
                                    })($v);
                                }
                            })($input->getBody());
                        }
                    })($v);
                }

                if (null !== $v = $input->getRaw()) {
                    (static function (RawMessage $input) use (&$payload) {
                        $payload['Content']['Raw']['Data'] = base64_encode($input->getData() ?? '');
                    })($v);
                }

                if (null !== $v = $input->getTemplate()) {
                    (static function (Template $input) use (&$payload) {
                        if (null !== $v = $input->getTemplateArn()) {
                            $payload['Content']['Template']['TemplateArn'] = $v;
                        }

                        if (null !== $v = $input->getTemplateData()) {
                            $payload['Content']['Template']['TemplateData'] = $v;
                        }
                    })($v);
                }
            })($this->Content);
        }

        (static function (array $input) use (&$payload, $indices) {
            $indices->k4a82301 = -1;
            foreach ($input as $value) {
                ++$indices->k4a82301;

                if (null !== $value) {
                    (static function (MessageTag $input) use (&$payload, $indices) {
                        $payload['EmailTags'][$indices->k4a82301]['Name'] = $input->getName();
                        $payload['EmailTags'][$indices->k4a82301]['Value'] = $input->getValue();
                    })($value);
                }
            }
        })($this->EmailTags);
        if (null !== $v = $this->ConfigurationSetName) {
            $payload['ConfigurationSetName'] = $v;
        }

        return json_encode($payload);
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/json'];

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

    public function setFromEmailAddress(?string $value): self
    {
        $this->FromEmailAddress = $value;

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

    public function validate(): void
    {
        if (null === $this->Destination) {
            throw new InvalidArgument(sprintf('Missing parameter "Destination" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        $this->Destination->validate();

        if (null === $this->Content) {
            throw new InvalidArgument(sprintf('Missing parameter "Content" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        $this->Content->validate();

        foreach ($this->EmailTags as $item) {
            $item->validate();
        }
    }
}
