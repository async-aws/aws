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
    private $DeploymentId;

    /**
     * The execution ID of a deployment's lifecycle hook. A deployment lifecycle hook is specified in the `hooks` section of
     * the AppSpec file.
     *
     * @var string|null
     */
    private $LifecycleEventHookExecutionId;

    /**
     * The result of a Lambda function that validates a deployment lifecycle event (`Succeeded` or `Failed`).
     *
     * @var null|LifecycleEventStatus::*
     */
    private $Status;

    /**
     * @param array{
     *   DeploymentId?: string,
     *   LifecycleEventHookExecutionId?: string,
     *   Status?: \AsyncAws\CodeDeploy\Enum\LifecycleEventStatus::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->DeploymentId = $input['DeploymentId'] ?? null;
        $this->LifecycleEventHookExecutionId = $input['LifecycleEventHookExecutionId'] ?? null;
        $this->Status = $input['Status'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeploymentId(): ?string
    {
        return $this->DeploymentId;
    }

    public function getLifecycleEventHookExecutionId(): ?string
    {
        return $this->LifecycleEventHookExecutionId;
    }

    /**
     * @return LifecycleEventStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->Status;
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

    public function setDeploymentId(?string $value): self
    {
        $this->DeploymentId = $value;

        return $this;
    }

    public function setLifecycleEventHookExecutionId(?string $value): self
    {
        $this->LifecycleEventHookExecutionId = $value;

        return $this;
    }

    /**
     * @param LifecycleEventStatus::*|null $value
     */
    public function setStatus(?string $value): self
    {
        $this->Status = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->DeploymentId) {
            $payload['deploymentId'] = $v;
        }
        if (null !== $v = $this->LifecycleEventHookExecutionId) {
            $payload['lifecycleEventHookExecutionId'] = $v;
        }
        if (null !== $v = $this->Status) {
            if (!LifecycleEventStatus::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "LifecycleEventStatus".', __CLASS__, $v));
            }
            $payload['status'] = $v;
        }

        return $payload;
    }
}
