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
     *   Destination: \AsyncAws\Ses\Input\Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   Content: \AsyncAws\Ses\Input\EmailContent|array,
     *   EmailTags?: \AsyncAws\Ses\Input\MessageTag[],
     *   ConfigurationSetName?: string,
     * }|SendEmailRequest $input
     */
    public function sendEmail($input): SendEmailResponse
    {
        $input = SendEmailRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new SendEmailResponse($response);
    }

    protected function getServiceCode(): string
    {
        return 'email';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
