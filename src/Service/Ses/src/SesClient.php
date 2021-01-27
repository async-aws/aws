<?php

namespace AsyncAws\Ses;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Result\SendEmailResponse;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\ListManagementOptions;
use AsyncAws\Ses\ValueObject\MessageTag;

class SesClient extends AbstractApi
{
    /**
     * Sends an email message. You can use the Amazon SES API v2 to send two types of messages:.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference/API_SendEmail.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#sendemail
     *
     * @param array{
     *   FromEmailAddress?: string,
     *   FromEmailAddressIdentityArn?: string,
     *   Destination?: Destination|array,
     *   ReplyToAddresses?: string[],
     *   FeedbackForwardingEmailAddress?: string,
     *   FeedbackForwardingEmailAddressIdentityArn?: string,
     *   Content: EmailContent|array,
     *   EmailTags?: MessageTag[],
     *   ConfigurationSetName?: string,
     *   ListManagementOptions?: ListManagementOptions|array,
     *   @region?: string,
     * }|SendEmailRequest $input
     */
    public function sendEmail($input): SendEmailResponse
    {
        $input = SendEmailRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendEmail', 'region' => $input->getRegion()]));

        return new SendEmailResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://email.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://email-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://email.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'ses',
            'signVersions' => ['v4'],
        ];
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
