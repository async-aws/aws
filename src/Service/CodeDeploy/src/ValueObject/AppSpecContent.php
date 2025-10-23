<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * A revision for an Lambda or Amazon ECS deployment that is a YAML-formatted or JSON-formatted string. For Lambda and
 * Amazon ECS deployments, the revision is the same as the AppSpec file. This method replaces the deprecated `RawString`
 * data type.
 */
final class AppSpecContent
{
    /**
     * The YAML-formatted or JSON-formatted revision string.
     *
     * For an Lambda deployment, the content includes a Lambda function name, the alias for its original version, and the
     * alias for its replacement version. The deployment shifts traffic from the original version of the Lambda function to
     * the replacement version.
     *
     * For an Amazon ECS deployment, the content includes the task name, information about the load balancer that serves
     * traffic to the container, and more.
     *
     * For both types of deployments, the content can specify Lambda functions that run at specified hooks, such as
     * `BeforeInstall`, during a deployment.
     *
     * @var string|null
     */
    private $content;

    /**
     * The SHA256 hash value of the revision content.
     *
     * @var string|null
     */
    private $sha256;

    /**
     * @param array{
     *   content?: string|null,
     *   sha256?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->content = $input['content'] ?? null;
        $this->sha256 = $input['sha256'] ?? null;
    }

    /**
     * @param array{
     *   content?: string|null,
     *   sha256?: string|null,
     * }|AppSpecContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getSha256(): ?string
    {
        return $this->sha256;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->content) {
            $payload['content'] = $v;
        }
        if (null !== $v = $this->sha256) {
            $payload['sha256'] = $v;
        }

        return $payload;
    }
}
