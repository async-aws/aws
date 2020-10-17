<?php

namespace AsyncAws\Ecr\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetAuthorizationTokenRequest extends Input
{
    /**
     * A list of AWS account IDs that are associated with the registries for which to get AuthorizationData objects. If you
     * do not specify a registry, the default registry is assumed.
     *
     * @var string[]|null
     */
    private $registryIds;

    /**
     * @param array{
     *   registryIds?: string[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->registryIds = $input['registryIds'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getRegistryIds(): array
    {
        return $this->registryIds ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonEC2ContainerRegistry_V20150921.GetAuthorizationToken',
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
    public function setRegistryIds(array $value): self
    {
        $this->registryIds = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->registryIds) {
            $index = -1;
            $payload['registryIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['registryIds'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
