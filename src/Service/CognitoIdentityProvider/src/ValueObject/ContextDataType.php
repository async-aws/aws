<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contextual data such as the user's device fingerprint, IP address, or location used for evaluating the risk of an
 * unexpected event by Amazon Cognito advanced security.
 */
final class ContextDataType
{
    /**
     * Source IP address of your user.
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
     * Encoded data containing device fingerprinting details, collected using the Amazon Cognito context data collection
     * library.
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
