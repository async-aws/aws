<?php

namespace AsyncAws\SsoOidc;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\SsoOidc\Exception\AccessDeniedException;
use AsyncAws\SsoOidc\Exception\AuthorizationPendingException;
use AsyncAws\SsoOidc\Exception\ExpiredTokenException;
use AsyncAws\SsoOidc\Exception\InternalServerException;
use AsyncAws\SsoOidc\Exception\InvalidClientException;
use AsyncAws\SsoOidc\Exception\InvalidClientMetadataException;
use AsyncAws\SsoOidc\Exception\InvalidGrantException;
use AsyncAws\SsoOidc\Exception\InvalidRedirectUriException;
use AsyncAws\SsoOidc\Exception\InvalidRequestException;
use AsyncAws\SsoOidc\Exception\InvalidScopeException;
use AsyncAws\SsoOidc\Exception\SlowDownException;
use AsyncAws\SsoOidc\Exception\UnauthorizedClientException;
use AsyncAws\SsoOidc\Exception\UnsupportedGrantTypeException;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;
use AsyncAws\SsoOidc\Input\StartDeviceAuthorizationRequest;
use AsyncAws\SsoOidc\Result\CreateTokenResponse;
use AsyncAws\SsoOidc\Result\RegisterClientResponse;
use AsyncAws\SsoOidc\Result\StartDeviceAuthorizationResponse;

class SsoOidcClient extends AbstractApi
{
    /**
     * Creates and returns access and refresh tokens for clients that are authenticated using client secrets. The access
     * token can be used to fetch short-lived credentials for the assigned AWS accounts or to access application APIs using
     * `bearer` authentication.
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/API_CreateToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-oidc-2019-06-10.html#createtoken
     *
     * @param array{
     *   clientId: string,
     *   clientSecret: string,
     *   grantType: string,
     *   deviceCode?: string|null,
     *   code?: string|null,
     *   refreshToken?: string|null,
     *   scope?: string[]|null,
     *   redirectUri?: string|null,
     *   codeVerifier?: string|null,
     *   '@region'?: string|null,
     * }|CreateTokenRequest $input
     *
     * @throws AccessDeniedException
     * @throws AuthorizationPendingException
     * @throws ExpiredTokenException
     * @throws InternalServerException
     * @throws InvalidClientException
     * @throws InvalidGrantException
     * @throws InvalidRequestException
     * @throws InvalidScopeException
     * @throws SlowDownException
     * @throws UnauthorizedClientException
     * @throws UnsupportedGrantTypeException
     */
    public function createToken($input): CreateTokenResponse
    {
        $input = CreateTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'AuthorizationPendingException' => AuthorizationPendingException::class,
            'ExpiredTokenException' => ExpiredTokenException::class,
            'InternalServerException' => InternalServerException::class,
            'InvalidClientException' => InvalidClientException::class,
            'InvalidGrantException' => InvalidGrantException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'InvalidScopeException' => InvalidScopeException::class,
            'SlowDownException' => SlowDownException::class,
            'UnauthorizedClientException' => UnauthorizedClientException::class,
            'UnsupportedGrantTypeException' => UnsupportedGrantTypeException::class,
        ]]));

        return new CreateTokenResponse($response);
    }

    /**
     * Registers a public client with IAM Identity Center. This allows clients to perform authorization using
     * the authorization code grant with Proof Key for Code Exchange (PKCE) or the device code grant.
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/API_RegisterClient.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-oidc-2019-06-10.html#registerclient
     *
     * @param array{
     *   clientName: string,
     *   clientType: string,
     *   scopes?: string[]|null,
     *   redirectUris?: string[]|null,
     *   grantTypes?: string[]|null,
     *   issuerUrl?: string|null,
     *   entitledApplicationArn?: string|null,
     *   '@region'?: string|null,
     * }|RegisterClientRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidClientMetadataException
     * @throws InvalidRedirectUriException
     * @throws InvalidRequestException
     * @throws InvalidScopeException
     * @throws SlowDownException
     * @throws UnsupportedGrantTypeException
     */
    public function registerClient($input): RegisterClientResponse
    {
        $input = RegisterClientRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RegisterClient', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidClientMetadataException' => InvalidClientMetadataException::class,
            'InvalidRedirectUriException' => InvalidRedirectUriException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'InvalidScopeException' => InvalidScopeException::class,
            'SlowDownException' => SlowDownException::class,
            'UnsupportedGrantTypeException' => UnsupportedGrantTypeException::class,
        ]]));

        return new RegisterClientResponse($response);
    }

    /**
     * Initiates device authorization by requesting a pair of verification codes from the authorization service.
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/API_StartDeviceAuthorization.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-oidc-2019-06-10.html#startdeviceauthorization
     *
     * @param array{
     *   clientId: string,
     *   clientSecret: string,
     *   startUrl: string,
     *   '@region'?: string|null,
     * }|StartDeviceAuthorizationRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidClientException
     * @throws InvalidRequestException
     * @throws SlowDownException
     * @throws UnauthorizedClientException
     */
    public function startDeviceAuthorization($input): StartDeviceAuthorizationResponse
    {
        $input = StartDeviceAuthorizationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartDeviceAuthorization', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidClientException' => InvalidClientException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'SlowDownException' => SlowDownException::class,
            'UnauthorizedClientException' => UnauthorizedClientException::class,
        ]]));

        return new StartDeviceAuthorizationResponse($response);
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
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://oidc.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'sso-oauth',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://oidc.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'sso-oauth',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://oidc.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'sso-oauth',
            'signVersions' => ['v4'],
        ];
    }
}
