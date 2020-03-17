<?php

namespace AsyncAws\Ses;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Result\SendEmailResponse;

class SesClient extends AbstractApi
{
    /**
     * Sends an email message. You can use the Amazon SES API v2 to send two types of messages:.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#sendemail
     *
     * @param array{
     *   FromEmailAddress?: string,
     *   Destination: \AsyncAws\Ses\ValueObject\Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   Content: \AsyncAws\Ses\ValueObject\EmailContent|array,
     *   EmailTags?: \AsyncAws\Ses\ValueObject\MessageTag[],
     *   ConfigurationSetName?: string,
     * }|SendEmailRequest $input
     */
    public function sendEmail($input): SendEmailResponse
    {
        $response = $this->getResponse(SendEmailRequest::create($input)->request());

        return new SendEmailResponse($response, $this->httpClient);
    }

    protected function getServiceCode(): string
    {
        return 'email';
    }

    protected function getSignatureScopeName(): string
    {
        return 'ses';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
