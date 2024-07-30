<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Input for CreatePlatformEndpoint action.
 */
final class CreatePlatformEndpointInput extends Input
{
    /**
     * `PlatformApplicationArn` returned from CreatePlatformApplication is used to create a an endpoint.
     *
     * @required
     *
     * @var string|null
     */
    private $platformApplicationArn;

    /**
     * Unique identifier created by the notification service for an app on a device. The specific name for Token will vary,
     * depending on which notification service is being used. For example, when using APNS as the notification service, you
     * need the device token. Alternatively, when using GCM (Firebase Cloud Messaging) or ADM, the device token equivalent
     * is called the registration ID.
     *
     * @required
     *
     * @var string|null
     */
    private $token;

    /**
     * Arbitrary user data to associate with the endpoint. Amazon SNS does not use this data. The data must be in UTF-8
     * format and less than 2KB.
     *
     * @var string|null
     */
    private $customUserData;

    /**
     * For a list of attributes, see `SetEndpointAttributes` [^1].
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/api/API_SetEndpointAttributes.html
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * @param array{
     *   PlatformApplicationArn?: string,
     *   Token?: string,
     *   CustomUserData?: null|string,
     *   Attributes?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->platformApplicationArn = $input['PlatformApplicationArn'] ?? null;
        $this->token = $input['Token'] ?? null;
        $this->customUserData = $input['CustomUserData'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   PlatformApplicationArn?: string,
     *   Token?: string,
     *   CustomUserData?: null|string,
     *   Attributes?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|CreatePlatformEndpointInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getCustomUserData(): ?string
    {
        return $this->customUserData;
    }

    public function getPlatformApplicationArn(): ?string
    {
        return $this->platformApplicationArn;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'CreatePlatformEndpoint', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setAttributes(array $value): self
    {
        $this->attributes = $value;

        return $this;
    }

    public function setCustomUserData(?string $value): self
    {
        $this->customUserData = $value;

        return $this;
    }

    public function setPlatformApplicationArn(?string $value): self
    {
        $this->platformApplicationArn = $value;

        return $this;
    }

    public function setToken(?string $value): self
    {
        $this->token = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->platformApplicationArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "PlatformApplicationArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PlatformApplicationArn'] = $v;
        if (null === $v = $this->token) {
            throw new InvalidArgument(\sprintf('Missing parameter "Token" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Token'] = $v;
        if (null !== $v = $this->customUserData) {
            $payload['CustomUserData'] = $v;
        }
        if (null !== $v = $this->attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
        }

        return $payload;
    }
}
