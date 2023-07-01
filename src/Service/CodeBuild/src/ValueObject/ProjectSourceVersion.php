<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A source identifier and its corresponding version.
 */
final class ProjectSourceVersion
{
    /**
     * An identifier for a source in the build project. The identifier can only contain alphanumeric characters and
     * underscores, and must be less than 128 characters in length.
     *
     * @var string
     */
    private $sourceIdentifier;

    /**
     * The source version for the corresponding source identifier. If specified, must be one of:.
     *
     * - For CodeCommit: the commit ID, branch, or Git tag to use.
     * - For GitHub: the commit ID, pull request ID, branch name, or tag name that corresponds to the version of the source
     *   code you want to build. If a pull request ID is specified, it must use the format `pr/pull-request-ID` (for
     *   example, `pr/25`). If a branch name is specified, the branch's HEAD commit ID is used. If not specified, the
     *   default branch's HEAD commit ID is used.
     * - For Bitbucket: the commit ID, branch name, or tag name that corresponds to the version of the source code you want
     *   to build. If a branch name is specified, the branch's HEAD commit ID is used. If not specified, the default
     *   branch's HEAD commit ID is used.
     * - For Amazon S3: the version ID of the object that represents the build input ZIP file to use.
     *
     * For more information, see Source Version Sample with CodeBuild [^1] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/sample-source-version.html
     *
     * @var string
     */
    private $sourceVersion;

    /**
     * @param array{
     *   sourceIdentifier: string,
     *   sourceVersion: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceIdentifier = $input['sourceIdentifier'] ?? $this->throwException(new InvalidArgument('Missing required field "sourceIdentifier".'));
        $this->sourceVersion = $input['sourceVersion'] ?? $this->throwException(new InvalidArgument('Missing required field "sourceVersion".'));
    }

    /**
     * @param array{
     *   sourceIdentifier: string,
     *   sourceVersion: string,
     * }|ProjectSourceVersion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSourceIdentifier(): string
    {
        return $this->sourceIdentifier;
    }

    public function getSourceVersion(): string
    {
        return $this->sourceVersion;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->sourceIdentifier;
        $payload['sourceIdentifier'] = $v;
        $v = $this->sourceVersion;
        $payload['sourceVersion'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
