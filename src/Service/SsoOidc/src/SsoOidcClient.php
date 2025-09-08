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
use AsyncAws\SsoOidc\Exception\InvalidGrantException;
use AsyncAws\SsoOidc\Exception\InvalidRequestException;
use AsyncAws\SsoOidc\Exception\InvalidScopeException;
use AsyncAws\SsoOidc\Exception\SlowDownException;
use AsyncAws\SsoOidc\Exception\UnauthorizedClientException;
use AsyncAws\SsoOidc\Exception\UnsupportedGrantTypeException;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Result\CreateTokenResponse;

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
        }

        return [
            'endpoint' => "https://oidc.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'sso-oauth',
            'signVersions' => ['v4'],
        ];
    }
}
