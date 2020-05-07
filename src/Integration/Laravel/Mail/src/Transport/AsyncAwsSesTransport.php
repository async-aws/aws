<?php

namespace AsyncAws\Illuminate\Mail\Transport;

use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\RawMessage;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;

/**
 * This class is a port from Illuminate\Mail\Transport\SesTransport.
 */
class AsyncAwsSesTransport extends Transport
{
    /**
     * The Amazon SES instance.
     *
     * @var SesClient
     */
    protected $ses;

    /**
     * The Amazon SES transmission options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Create a new SES transport instance.
     *
     * @param array $options
     *
     * @return void
     */
    public function __construct(SesClient $ses, $options = [])
    {
        $this->ses = $ses;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $result = $this->ses->sendEmail(
            array_merge($this->options, [
                'FromEmailAddress' => key($message->getSender() ?: $message->getFrom()),
                'Content' => new EmailContent([
                    'Raw' => new RawMessage(['Data' => $message->toString()]),
                ]),
            ])
        );

        $message->getHeaders()->addTextHeader('X-SES-Message-ID', $result->getMessageId());

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get the Amazon SES client for the SesTransport instance.
     *
     * @return SesClient
     */
    public function ses()
    {
        return $this->ses;
    }

    /**
     * Get the transmission options being used by the transport.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the transmission options being used by the transport.
     *
     * @return array
     */
    public function setOptions(array $options)
    {
        return $this->options = $options;
    }
}
