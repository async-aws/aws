<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetParametersRequest extends Input
{
    /**
     * Names of the parameters for which you want to query information.
     *
     * @required
     *
     * @var string[]
     */
    private $Names;

    /**
     * Return decrypted secure string value. Return decrypted values for secure string parameters. This flag is ignored for
     * String and StringList parameter types.
     *
     * @var bool|null
     */
    private $WithDecryption;

    /**
     * @param array{
     *   Names?: string[],
     *   WithDecryption?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Names = $input['Names'] ?? [];
        $this->WithDecryption = $input['WithDecryption'] ?? null;
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
        return $this->Names;
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
            'X-Amz-Target' => 'AmazonSSM.GetParameters',
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

    /**
     * @param string[] $value
     */
    public function setNames(array $value): self
    {
        $this->Names = $value;

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

        $index = -1;
        foreach ($this->Names as $listValue) {
            ++$index;
            $payload['Names'][$index] = $listValue;
        }

        if (null !== $v = $this->WithDecryption) {
            $payload['WithDecryption'] = (bool) $v;
        }

        return $payload;
    }
}
