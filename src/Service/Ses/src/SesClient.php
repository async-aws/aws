<?php

namespace AsyncAws\Ses;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Exception\AccountSuspendedException;
use AsyncAws\Ses\Exception\AlreadyExistsException;
use AsyncAws\Ses\Exception\BadRequestException;
use AsyncAws\Ses\Exception\ConcurrentModificationException;
use AsyncAws\Ses\Exception\LimitExceededException;
use AsyncAws\Ses\Exception\MailFromDomainNotVerifiedException;
use AsyncAws\Ses\Exception\MessageRejectedException;
use AsyncAws\Ses\Exception\NotFoundException;
use AsyncAws\Ses\Exception\SendingPausedException;
use AsyncAws\Ses\Exception\TooManyRequestsException;
use AsyncAws\Ses\Input\CreateEmailIdentityRequest;
use AsyncAws\Ses\Input\DeleteSuppressedDestinationRequest;
use AsyncAws\Ses\Input\GetEmailIdentityRequest;
use AsyncAws\Ses\Input\GetSuppressedDestinationRequest;
use AsyncAws\Ses\Input\PutEmailIdentityDkimSigningAttributesRequest;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\Result\CreateEmailIdentityResponse;
use AsyncAws\Ses\Result\DeleteSuppressedDestinationResponse;
use AsyncAws\Ses\Result\GetEmailIdentityResponse;
use AsyncAws\Ses\Result\GetSuppressedDestinationResponse;
use AsyncAws\Ses\Result\PutEmailIdentityDkimSigningAttributesResponse;
use AsyncAws\Ses\Result\SendEmailResponse;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\ListManagementOptions;
use AsyncAws\Ses\ValueObject\MessageTag;
use AsyncAws\Ses\ValueObject\Tag;

class SesClient extends AbstractApi
{
    /**
     * Starts the process of verifying an email identity. An *identity* is an email address or domain that you use when you
     * send email. Before you can use an identity to send email, you first have to verify it. By verifying an identity, you
     * demonstrate that you're the owner of the identity, and that you've given Amazon SES API v2 permission to send email
     * from the identity.
     *
     * When you verify an email address, Amazon SES sends an email to the address. Your email address is verified as soon as
     * you follow the link in the verification email.
     *
     * When you verify a domain without specifying the `DkimSigningAttributes` object, this operation provides a set of DKIM
     * tokens. You can convert these tokens into CNAME records, which you then add to the DNS configuration for your domain.
     * Your domain is verified when Amazon SES detects these records in the DNS configuration for your domain. This
     * verification method is known as Easy DKIM [^1].
     *
     * Alternatively, you can perform the verification process by providing your own public-private key pair. This
     * verification method is known as Bring Your Own DKIM (BYODKIM). To use BYODKIM, your call to the `CreateEmailIdentity`
     * operation has to include the `DkimSigningAttributes` object. When you specify this object, you provide a selector (a
     * component of the DNS record name that identifies the public key to use for DKIM authentication) and a private key.
     *
     * When you verify a domain, this operation provides a set of DKIM tokens, which you can convert into CNAME tokens. You
     * add these CNAME tokens to the DNS configuration for your domain. Your domain is verified when Amazon SES detects
     * these records in the DNS configuration for your domain. For some DNS providers, it can take 72 hours or more to
     * complete the domain verification process.
     *
     * Additionally, you can associate an existing configuration set with the email identity that you're verifying.
     *
     * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/easy-dkim.html
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_CreateEmailIdentity.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#createemailidentity
     *
     * @param array{
     *   EmailIdentity: string,
     *   Tags?: array<Tag|array>|null,
     *   DkimSigningAttributes?: DkimSigningAttributes|array|null,
     *   ConfigurationSetName?: string|null,
     *   '@region'?: string|null,
     * }|CreateEmailIdentityRequest $input
     *
     * @throws AlreadyExistsException
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws LimitExceededException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     */
    public function createEmailIdentity($input): CreateEmailIdentityResponse
    {
        $input = CreateEmailIdentityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateEmailIdentity', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AlreadyExistsException' => AlreadyExistsException::class,
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotFoundException' => NotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new CreateEmailIdentityResponse($response);
    }

    /**
     * Removes an email address from the suppression list for your account or for a specific tenant. To target a tenant's
     * suppression list, specify the `TenantName` parameter. If you omit `TenantName`, the address is removed from the
     * account-level suppression list.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_DeleteSuppressedDestination.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#deletesuppresseddestination
     *
     * @param array{
     *   EmailAddress: string,
     *   TenantName?: string|null,
     *   '@region'?: string|null,
     * }|DeleteSuppressedDestinationRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     */
    public function deleteSuppressedDestination($input): DeleteSuppressedDestinationResponse
    {
        $input = DeleteSuppressedDestinationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteSuppressedDestination', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new DeleteSuppressedDestinationResponse($response);
    }

    /**
     * Provides information about a specific identity, including the identity's verification status, sending authorization
     * policies, its DKIM authentication status, and its custom Mail-From settings.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetEmailIdentity.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#getemailidentity
     *
     * @param array{
     *   EmailIdentity: string,
     *   '@region'?: string|null,
     * }|GetEmailIdentityRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     */
    public function getEmailIdentity($input): GetEmailIdentityResponse
    {
        $input = GetEmailIdentityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetEmailIdentity', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new GetEmailIdentityResponse($response);
    }

    /**
     * Retrieves information about a specific email address that's on the suppression list for your account or for a
     * specific tenant. To target a tenant's suppression list, specify the `TenantName` parameter. If you omit `TenantName`,
     * the operation targets the account-level suppression list.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetSuppressedDestination.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#getsuppresseddestination
     *
     * @param array{
     *   EmailAddress: string,
     *   TenantName?: string|null,
     *   '@region'?: string|null,
     * }|GetSuppressedDestinationRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     */
    public function getSuppressedDestination($input): GetSuppressedDestinationResponse
    {
        $input = GetSuppressedDestinationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSuppressedDestination', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new GetSuppressedDestinationResponse($response);
    }

    /**
     * Used to configure or change the DKIM authentication settings for an email domain identity. You can use this operation
     * to do any of the following:
     *
     * - Update the signing attributes for an identity that uses Bring Your Own DKIM (BYODKIM).
     * - Update the key length that should be used for Easy DKIM.
     * - Change from using no DKIM authentication to using Easy DKIM.
     * - Change from using no DKIM authentication to using BYODKIM.
     * - Change from using Easy DKIM to using BYODKIM.
     * - Change from using BYODKIM to using Easy DKIM.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#putemailidentitydkimsigningattributes
     *
     * @param array{
     *   EmailIdentity: string,
     *   SigningAttributesOrigin: DkimSigningAttributesOrigin::*,
     *   SigningAttributes?: DkimSigningAttributes|array|null,
     *   '@region'?: string|null,
     * }|PutEmailIdentityDkimSigningAttributesRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     */
    public function putEmailIdentityDkimSigningAttributes($input): PutEmailIdentityDkimSigningAttributesResponse
    {
        $input = PutEmailIdentityDkimSigningAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutEmailIdentityDkimSigningAttributes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new PutEmailIdentityDkimSigningAttributesResponse($response);
    }

    /**
     * Sends an email message. You can use the Amazon SES API v2 to send the following types of messages:
     *
     * - **Simple** – A standard email message. When you create this type of message, you specify the sender, the
     *   recipient, and the message body, and Amazon SES assembles the message for you.
     * - **Raw** – A raw, MIME-formatted email message. When you send this type of email, you have to specify all of the
     *   message headers, as well as the message body. You can use this message type to send messages that contain
     *   attachments. The message that you specify has to be a valid MIME message.
     * - **Templated** – A message that contains personalization tags. When you send this type of email, Amazon SES API v2
     *   automatically replaces the tags with values that you specify.
     *
     * @see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_SendEmail.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2019-09-27.html#sendemail
     *
     * @param array{
     *   FromEmailAddress?: string|null,
     *   FromEmailAddressIdentityArn?: string|null,
     *   Destination?: Destination|array|null,
     *   ReplyToAddresses?: string[]|null,
     *   FeedbackForwardingEmailAddress?: string|null,
     *   FeedbackForwardingEmailAddressIdentityArn?: string|null,
     *   Content: EmailContent|array,
     *   EmailTags?: array<MessageTag|array>|null,
     *   ConfigurationSetName?: string|null,
     *   EndpointId?: string|null,
     *   TenantName?: string|null,
     *   ListManagementOptions?: ListManagementOptions|array|null,
     *   '@region'?: string|null,
     * }|SendEmailRequest $input
     *
     * @throws AccountSuspendedException
     * @throws BadRequestException
     * @throws LimitExceededException
     * @throws MailFromDomainNotVerifiedException
     * @throws MessageRejectedException
     * @throws NotFoundException
     * @throws SendingPausedException
     * @throws TooManyRequestsException
     */
    public function sendEmail($input): SendEmailResponse
    {
        $input = SendEmailRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendEmail', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccountSuspendedException' => AccountSuspendedException::class,
            'BadRequestException' => BadRequestException::class,
            'LimitExceededException' => LimitExceededException::class,
            'MailFromDomainNotVerifiedException' => MailFromDomainNotVerifiedException::class,
            'MessageRejected' => MessageRejectedException::class,
            'NotFoundException' => NotFoundException::class,
            'SendingPausedException' => SendingPausedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

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
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://email.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://email-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://email-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://email-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://email-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://email-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ses',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://email-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
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
