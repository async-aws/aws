<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contextual user data used for evaluating the risk of an authentication event by user pool threat protection.
 */
final class ContextDataType
{
    /**
     * The source IP address of your user's device.
     *
     * @var string
     */
    private $ipAddress;

    /**
     * The name of your application's service endpoint.
     *
     * @var string
     */
    private $serverName;

    /**
     * The path of your application's service endpoint.
     *
     * @var string
     */
    private $serverPath;

    /**
     * The HTTP headers from your user's authentication request.
     *
     * @var HttpHeader[]
     */
    private $httpHeaders;

    /**
     * Encoded device-fingerprint details that your app collected with the Amazon Cognito context data collection library.
     * For more information, see Adding user device and session data to API requests [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pool-settings-adaptive-authentication.html#user-pool-settings-adaptive-authentication-device-fingerprint
     *
     * @var string|null
     */
    private $encodedData;

    /**
     * @param array{
     *   IpAddress: string,
     *   ServerName: string,
     *   ServerPath: string,
     *   HttpHeaders: array<HttpHeader|array>,
     *   EncodedData?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ipAddress = $input['IpAddress'] ?? $this->throwException(new InvalidArgument('Missing required field "IpAddress".'));
        $this->serverName = $input['ServerName'] ?? $this->throwException(new InvalidArgument('Missing required field "ServerName".'));
        $this->serverPath = $input['ServerPath'] ?? $this->throwException(new InvalidArgument('Missing required field "ServerPath".'));
        $this->httpHeaders = isset($input['HttpHeaders']) ? array_map([HttpHeader::class, 'create'], $input['HttpHeaders']) : $this->throwException(new InvalidArgument('Missing required field "HttpHeaders".'));
        $this->encodedData = $input['EncodedData'] ?? null;
    }

    /**
     * @param array{
     *   IpAddress: string,
     *   ServerName: string,
     *   ServerPath: string,
     *   HttpHeaders: array<HttpHeader|array>,
     *   EncodedData?: string|null,
     * }|ContextDataType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncodedData(): ?string
    {
        return $this->encodedData;
    }

    /**
     * @return HttpHeader[]
     */
    public function getHttpHeaders(): array
    {
        return $this->httpHeaders;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getServerPath(): string
    {
        return $this->serverPath;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->ipAddress;
        $payload['IpAddress'] = $v;
        $v = $this->serverName;
        $payload['ServerName'] = $v;
        $v = $this->serverPath;
        $payload['ServerPath'] = $v;
        $v = $this->httpHeaders;

        $index = -1;
        $payload['HttpHeaders'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['HttpHeaders'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->encodedData) {
            $payload['EncodedData'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
