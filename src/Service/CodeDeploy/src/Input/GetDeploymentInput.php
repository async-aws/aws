<?php

namespace AsyncAws\CodeDeploy\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a `GetDeployment` operation.
 */
final class GetDeploymentInput extends Input
{
    /**
     * The unique ID of a deployment associated with the IAM user or AWS account.
     *
     * @required
     *
     * @var string|null
     */
    private $deploymentId;

    /**
     * @param array{
     *   deploymentId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->deploymentId = $input['deploymentId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeploymentId(): ?string
    {
        return $this->deploymentId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeDeploy_20141006.GetDeployment',
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

    public function setDeploymentId(?string $value): self
    {
        $this->deploymentId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->deploymentId) {
            throw new InvalidArgument(sprintf('Missing parameter "deploymentId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['deploymentId'] = $v;

        return $payload;
    }
}
