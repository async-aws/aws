<?php

namespace AsyncAws\Ses;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
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
     *   @region?: string,
     * }|SendEmailRequest $input
     */
    public function sendEmail($input): SendEmailResponse
    {
        $input = SendEmailRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendEmail', 'region' => $input->getRegion()]));

        return new SendEmailResponse($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'ap-south-1':
            case 'ap-southeast-2':
            case 'eu-central-1':
            case 'eu-west-1':
            case 'us-east-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://email.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'email',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://email.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'email',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://email-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'email',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Ses".', $region));
    }

    protected function getServiceCode(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'email';
    }

    protected function getSignatureScopeName(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'ses';
    }

    protected function getSignatureVersion(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'v4';
    }
}
