<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteServiceSpecificCredentialRequest extends Input
{
    /**
     * The name of the IAM user associated with the service-specific credential. If this value is not specified, then the
     * operation assumes the user whose credentials are used to call the operation.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @var string|null
     */
    private $userName;

    /**
     * The unique identifier of the service-specific credential. You can get this value by calling
     * ListServiceSpecificCredentials [^1].
     *
     * This parameter allows (through its regex pattern [^2]) a string of characters that can consist of any upper or
     * lowercased letter or digit.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListServiceSpecificCredentials.html
     * [^2]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $serviceSpecificCredentialId;

    /**
     * @param array{
     *   UserName?: null|string,
     *   ServiceSpecificCredentialId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->serviceSpecificCredentialId = $input['ServiceSpecificCredentialId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: null|string,
     *   ServiceSpecificCredentialId?: string,
     *   '@region'?: string|null,
     * }|DeleteServiceSpecificCredentialRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getServiceSpecificCredentialId(): ?string
    {
        return $this->serviceSpecificCredentialId;
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
        $body = http_build_query(['Action' => 'DeleteServiceSpecificCredential', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setServiceSpecificCredentialId(?string $value): self
    {
        $this->serviceSpecificCredentialId = $value;

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
        if (null !== $v = $this->userName) {
            $payload['UserName'] = $v;
        }
        if (null === $v = $this->serviceSpecificCredentialId) {
            throw new InvalidArgument(\sprintf('Missing parameter "ServiceSpecificCredentialId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServiceSpecificCredentialId'] = $v;

        return $payload;
    }
}
