<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteParametersRequest extends Input
{
    /**
     * The names of the parameters to delete. After deleting a parameter, wait for at least 30 seconds to create a parameter
     * with the same name.
     *
     * > You can't enter the Amazon Resource Name (ARN) for a parameter, only the parameter name itself.
     *
     * @required
     *
     * @var string[]|null
     */
    private $names;

    /**
     * @param array{
     *   Names?: string[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->names = $input['Names'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Names?: string[],
     *   '@region'?: string|null,
     * }|DeleteParametersRequest $input
     */
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonSSM.DeleteParameters',
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

        return $payload;
    }
}
