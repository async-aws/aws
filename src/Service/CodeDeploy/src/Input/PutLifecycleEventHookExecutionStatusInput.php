<?php

namespace AsyncAws\CodeDeploy\Input;

use AsyncAws\CodeDeploy\Enum\LifecycleEventStatus;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutLifecycleEventHookExecutionStatusInput extends Input
{
    /**
     * The unique ID of a deployment. Pass this ID to a Lambda function that validates a deployment lifecycle event.
     *
     * @var string|null
     */
    private $deploymentId;

    /**
     * The execution ID of a deployment's lifecycle hook. A deployment lifecycle hook is specified in the `hooks` section of
     * the AppSpec file.
     *
     * @var string|null
     */
    private $lifecycleEventHookExecutionId;

    /**
     * The result of a Lambda function that validates a deployment lifecycle event. The values listed in **Valid Values**
     * are valid for lifecycle statuses in general; however, only `Succeeded` and `Failed` can be passed successfully in
     * your API call.
     *
     * @var LifecycleEventStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   deploymentId?: string|null,
     *   lifecycleEventHookExecutionId?: string|null,
     *   status?: LifecycleEventStatus::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->deploymentId = $input['deploymentId'] ?? null;
        $this->lifecycleEventHookExecutionId = $input['lifecycleEventHookExecutionId'] ?? null;
        $this->status = $input['status'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   deploymentId?: string|null,
     *   lifecycleEventHookExecutionId?: string|null,
     *   status?: LifecycleEventStatus::*|null,
     *   '@region'?: string|null,
     * }|PutLifecycleEventHookExecutionStatusInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeploymentId(): ?string
    {
        return $this->deploymentId;
    }

    public function getLifecycleEventHookExecutionId(): ?string
    {
        return $this->lifecycleEventHookExecutionId;
    }

    /**
     * @return LifecycleEventStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeDeploy_20141006.PutLifecycleEventHookExecutionStatus',
            'Accept' => 'application/json',
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

    public function setLifecycleEventHookExecutionId(?string $value): self
    {
        $this->lifecycleEventHookExecutionId = $value;

        return $this;
    }

    /**
     * @param LifecycleEventStatus::*|null $value
     */
    public function setStatus(?string $value): self
    {
        $this->status = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->deploymentId) {
            $payload['deploymentId'] = $v;
        }
        if (null !== $v = $this->lifecycleEventHookExecutionId) {
            $payload['lifecycleEventHookExecutionId'] = $v;
        }
        if (null !== $v = $this->status) {
            if (!LifecycleEventStatus::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "LifecycleEventStatus".', __CLASS__, $v));
            }
            $payload['status'] = $v;
        }

        return $payload;
    }
}
