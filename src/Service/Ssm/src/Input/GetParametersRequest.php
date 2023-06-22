<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetParametersRequest extends Input
{
    /**
     * Names of the parameters for which you want to query information.
     *
     * To query by parameter label, use `"Name": "name:label"`. To query by parameter version, use `"Name": "name:version"`.
     *
     * @required
     *
     * @var string[]|null
     */
    private $names;

    /**
     * Return decrypted secure string value. Return decrypted values for secure string parameters. This flag is ignored for
     * `String` and `StringList` parameter types.
     *
     * @var bool|null
     */
    private $withDecryption;

    /**
     * @param array{
     *   Names?: string[],
     *   WithDecryption?: bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->names = $input['Names'] ?? null;
        $this->withDecryption = $input['WithDecryption'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names ?? [];
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
            'X-Amz-Target' => 'AmazonSSM.GetParameters',
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

    /**
     * @param string[] $value
     */
    public function setNames(array $value): self
    {
        $this->names = $value;

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
        if (null === $v = $this->names) {
            throw new InvalidArgument(sprintf('Missing parameter "Names" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Names'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Names'][$index] = $listValue;
        }

        if (null !== $v = $this->withDecryption) {
            $payload['WithDecryption'] = (bool) $v;
        }

        return $payload;
    }
}
