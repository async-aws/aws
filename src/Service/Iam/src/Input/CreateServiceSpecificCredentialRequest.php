<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateServiceSpecificCredentialRequest extends Input
{
    /**
     * The name of the IAM user that is to be associated with the credentials. The new service-specific credentials have the
     * same permissions as the associated user except that they can be used only to access the specified service.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The name of the Amazon Web Services service that is to be associated with the credentials. The service you specify
     * here is the only service that can be accessed using these credentials.
     *
     * @required
     *
     * @var string|null
     */
    private $serviceName;

    /**
     * The number of days until the service specific credential expires. This field is only valid for Bedrock API keys and
     * must be a positive integer. When not specified, the credential will not expire.
     *
     * @var int|null
     */
    private $credentialAgeDays;

    /**
     * @param array{
     *   UserName?: string,
     *   ServiceName?: string,
     *   CredentialAgeDays?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->serviceName = $input['ServiceName'] ?? null;
        $this->credentialAgeDays = $input['CredentialAgeDays'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: string,
     *   ServiceName?: string,
     *   CredentialAgeDays?: int|null,
     *   '@region'?: string|null,
     * }|CreateServiceSpecificCredentialRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCredentialAgeDays(): ?int
    {
        return $this->credentialAgeDays;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
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
        $body = http_build_query(['Action' => 'CreateServiceSpecificCredential', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCredentialAgeDays(?int $value): self
    {
        $this->credentialAgeDays = $value;

        return $this;
    }

    public function setServiceName(?string $value): self
    {
        $this->serviceName = $value;

        return $this;
    }

    public function setUserName(?string $value): self
    {
        $this->userName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userName) {
            throw new InvalidArgument(\sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null === $v = $this->serviceName) {
            throw new InvalidArgument(\sprintf('Missing parameter "ServiceName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServiceName'] = $v;
        if (null !== $v = $this->credentialAgeDays) {
            $payload['CredentialAgeDays'] = $v;
        }

        return $payload;
    }
}
