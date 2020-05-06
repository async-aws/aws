<?php

namespace Illuminate\Mail\Transport;

use AsyncAws\Ses\SesClient;
use Swift_Mime_SimpleMessage;

class AsyncAwsSesTransport extends Transport
{
    /**
     * The Amazon SES instance.
     *
     * @var \AsyncAws\Ses\SesClient
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
     * @param  \AsyncAws\Ses\SesClient  $ses
     * @param  array  $options
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
            array_merge(
                $this->options, [
                    'Source' => key($message->getSender() ?: $message->getFrom()),
                    'RawMessage' => [
                        'Data' => $message->toString(),
                    ],
                ]
            )
        );

        $message->getHeaders()->addTextHeader('X-SES-Message-ID', $result->getMessageId());

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get the Amazon SES client for the SesTransport instance.
     *
     * @return \AsyncAws\Ses\SesClient
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
     * @param  array  $options
     * @return array
     */
    public function setOptions(array $options)
    {
        return $this->options = $options;
    }
}
