<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information that defines how the CodeBuild build project reports the build status to the source provider.
 */
final class BuildStatusConfig
{
    /**
     * Specifies the context of the build status CodeBuild sends to the source provider. The usage of this parameter depends
     * on the source provider.
     *
     * - `Bitbucket`:
     *
     *   This parameter is used for the `name` parameter in the Bitbucket commit status. For more information, see build
     *   [^1] in the Bitbucket API documentation.
     * - `GitHub/GitHub Enterprise Server`:
     *
     *   This parameter is used for the `context` parameter in the GitHub commit status. For more information, see Create a
     *   commit status [^2] in the GitHub developer guide.
     *
     * [^1]: https://developer.atlassian.com/bitbucket/api/2/reference/resource/repositories/%7Bworkspace%7D/%7Brepo_slug%7D/commit/%7Bnode%7D/statuses/build
     * [^2]: https://developer.github.com/v3/repos/statuses/#create-a-commit-status
     *
     * @var string|null
     */
    private $context;

    /**
     * Specifies the target url of the build status CodeBuild sends to the source provider. The usage of this parameter
     * depends on the source provider.
     *
     * - `Bitbucket`:
     *
     *   This parameter is used for the `url` parameter in the Bitbucket commit status. For more information, see build [^1]
     *   in the Bitbucket API documentation.
     * - `GitHub/GitHub Enterprise Server`:
     *
     *   This parameter is used for the `target_url` parameter in the GitHub commit status. For more information, see Create
     *   a commit status [^2] in the GitHub developer guide.
     *
     * [^1]: https://developer.atlassian.com/bitbucket/api/2/reference/resource/repositories/%7Bworkspace%7D/%7Brepo_slug%7D/commit/%7Bnode%7D/statuses/build
     * [^2]: https://developer.github.com/v3/repos/statuses/#create-a-commit-status
     *
     * @var string|null
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

    /**
     * @param array{
     *   context?: null|string,
     *   targetUrl?: null|string,
     * }|BuildStatusConfig $input
     */
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
