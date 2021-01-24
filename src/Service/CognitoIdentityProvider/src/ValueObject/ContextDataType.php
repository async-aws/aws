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
    private $IpAddress;

    /**
     * Your server endpoint where this API is invoked.
     */
    private $ServerName;

    /**
     * Your server path where this API is invoked.
     */
    private $ServerPath;

    /**
     * HttpHeaders received on your server in same order.
     */
    private $HttpHeaders;

    /**
     * Encoded data containing device fingerprinting details, collected using the Amazon Cognito context data collection
     * library.
     */
    private $EncodedData;

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
        $this->IpAddress = $input['IpAddress'] ?? null;
        $this->ServerName = $input['ServerName'] ?? null;
        $this->ServerPath = $input['ServerPath'] ?? null;
        $this->HttpHeaders = isset($input['HttpHeaders']) ? array_map([HttpHeader::class, 'create'], $input['HttpHeaders']) : null;
        $this->EncodedData = $input['EncodedData'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncodedData(): ?string
    {
        return $this->EncodedData;
    }

    /**
     * @return HttpHeader[]
     */
    public function getHttpHeaders(): array
    {
        return $this->HttpHeaders ?? [];
    }

    public function getIpAddress(): string
    {
        return $this->IpAddress;
    }

    public function getServerName(): string
    {
        return $this->ServerName;
    }

    public function getServerPath(): string
    {
        return $this->ServerPath;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->IpAddress) {
            throw new InvalidArgument(sprintf('Missing parameter "IpAddress" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IpAddress'] = $v;
        if (null === $v = $this->ServerName) {
            throw new InvalidArgument(sprintf('Missing parameter "ServerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServerName'] = $v;
        if (null === $v = $this->ServerPath) {
            throw new InvalidArgument(sprintf('Missing parameter "ServerPath" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServerPath'] = $v;
        if (null === $v = $this->HttpHeaders) {
            throw new InvalidArgument(sprintf('Missing parameter "HttpHeaders" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['HttpHeaders'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['HttpHeaders'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->EncodedData) {
            $payload['EncodedData'] = $v;
        }

        return $payload;
    }
}
