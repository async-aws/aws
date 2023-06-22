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
     * To query by parameter label, use `"Name": "name:label"`. To query by parameter version, use `"Name": "name:version"`.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * Return decrypted values for secure string parameters. This flag is ignored for `String` and `StringList` parameter
     * types.
     *
     * @var bool|null
     */
    private $withDecryption;

    /**
     * @param array{
     *   Name?: string,
     *   WithDecryption?: bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->withDecryption = $input['WithDecryption'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWithDecryption(): ?bool
    {
        return $this->withDecryption;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setWithDecryption(?bool $value): self
    {
        $this->withDecryption = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Name'] = $v;
        if (null !== $v = $this->withDecryption) {
            $payload['WithDecryption'] = (bool) $v;
        }

        return $payload;
    }
}
