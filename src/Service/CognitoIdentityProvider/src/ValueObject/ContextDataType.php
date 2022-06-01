<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contextual data about your user session, such as the device fingerprint, IP address, or location. Amazon Cognito
 * advanced security evaluates the risk of an authentication event based on the context that your app generates and
 * passes to Amazon Cognito when it makes API requests.
 */
final class ContextDataType
{
    /**
     * The source IP address of your user's device.
     */
    private $ipAddress;

    /**
     * Your server endpoint where this API is invoked.
     */
    private $serverName;

    /**
     * Your server path where this API is invoked.
     */
    private $serverPath;

    /**
     * HttpHeaders received on your server in same order.
     */
    private $httpHeaders;

    /**
     * Encoded device-fingerprint details that your app collected with the Amazon Cognito context data collection library.
     * For more information, see Adding user device and session data to API requests.
     *
     * @see https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pool-settings-adaptive-authentication.html#user-pool-settings-adaptive-authentication-device-fingerprint
     */
    private $encodedData;

    /**
     * @param array{
     *   IpAddress: string,
     *   ServerName: string,
     *   ServerPath: string,
     *   HttpHeaders: HttpHeader[],
     *   EncodedData?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ipAddress = $input['IpAddress'] ?? null;
        $this->serverName = $input['ServerName'] ?? null;
        $this->serverPath = $input['ServerPath'] ?? null;
        $this->httpHeaders = isset($input['HttpHeaders']) ? array_map([HttpHeader::class, 'create'], $input['HttpHeaders']) : null;
        $this->encodedData = $input['EncodedData'] ?? null;
    }

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
        return $this->httpHeaders ?? [];
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
        if (null === $v = $this->ipAddress) {
            throw new InvalidArgument(sprintf('Missing parameter "IpAddress" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IpAddress'] = $v;
        if (null === $v = $this->serverName) {
            throw new InvalidArgument(sprintf('Missing parameter "ServerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServerName'] = $v;
        if (null === $v = $this->serverPath) {
            throw new InvalidArgument(sprintf('Missing parameter "ServerPath" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServerPath'] = $v;
        if (null === $v = $this->httpHeaders) {
            throw new InvalidArgument(sprintf('Missing parameter "HttpHeaders" for "%s". The value cannot be null.', __CLASS__));
        }

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
}
