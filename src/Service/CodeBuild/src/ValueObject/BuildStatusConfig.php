<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information that defines how the build project reports the build status to the source provider. This option
 * is only used when the source provider is `GITHUB`, `GITHUB_ENTERPRISE`, or `BITBUCKET`.
 */
final class BuildStatusConfig
{
    /**
     * Specifies the context of the build status CodeBuild sends to the source provider. The usage of this parameter depends
     * on the source provider.
     */
    private $context;

    /**
     * Specifies the target url of the build status CodeBuild sends to the source provider. The usage of this parameter
     * depends on the source provider.
     */
    private $targetUrl;

    /**
     * @param array{
     *   context?: null|string,
     *   targetUrl?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->context = $input['context'] ?? null;
        $this->targetUrl = $input['targetUrl'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function getTargetUrl(): ?string
    {
        return $this->targetUrl;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->context) {
            $payload['context'] = $v;
        }
        if (null !== $v = $this->targetUrl) {
            $payload['targetUrl'] = $v;
        }

        return $payload;
    }
}
