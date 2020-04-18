<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetParameterRequest extends Input
{
    /**
     * The name of the parameter you want to query.
     *
     * @required
     *
     * @var string|null
     */
    private $Name;

    /**
     * Return decrypted values for secure string parameters. This flag is ignored for String and StringList parameter types.
     *
     * @var bool|null
     */
    private $WithDecryption;

    /**
     * @param array{
     *   Name?: string,
     *   WithDecryption?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Name = $input['Name'] ?? null;
        $this->WithDecryption = $input['WithDecryption'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getWithDecryption(): ?bool
    {
        return $this->WithDecryption;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonSSM.GetParameter',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setName(?string $value): self
    {
        $this->Name = $value;

        return $this;
    }

    public function setWithDecryption(?bool $value): self
    {
        $this->WithDecryption = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->WithDecryption) {
            $payload['WithDecryption'] = (bool) $v;
        }

        return $payload;
    }
}
