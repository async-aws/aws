<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\SourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the source code to be built.
 */
final class ProjectSource
{
    /**
     * The type of repository that contains the source code to be built. Valid values include:.
     */
    private $type;

    /**
     * Information about the location of the source code to be built. Valid values include:.
     */
    private $location;

    /**
     * Information about the Git clone depth for the build project.
     */
    private $gitCloneDepth;

    /**
     * Information about the Git submodules configuration for the build project.
     */
    private $gitSubmodulesConfig;

    /**
     * The buildspec file declaration to use for the builds in this build project.
     */
    private $buildspec;

    /**
     * Information about the authorization settings for CodeBuild to access the source code to be built.
     */
    private $auth;

    /**
     * Set to true to report the status of a build's start and finish to your source provider. This option is valid only
     * when your source provider is GitHub, GitHub Enterprise, or Bitbucket. If this is set and you use a different source
     * provider, an `invalidInputException` is thrown.
     */
    private $reportBuildStatus;

    /**
     * Contains information that defines how the build project reports the build status to the source provider. This option
     * is only used when the source provider is `GITHUB`, `GITHUB_ENTERPRISE`, or `BITBUCKET`.
     */
    private $buildStatusConfig;

    /**
     * Enable this flag to ignore SSL warnings while connecting to the project source code.
     */
    private $insecureSsl;

    /**
     * An identifier for this project source. The identifier can only contain alphanumeric characters and underscores, and
     * must be less than 128 characters in length.
     */
    private $sourceIdentifier;

    /**
     * @param array{
     *   type: SourceType::*,
     *   location?: null|string,
     *   gitCloneDepth?: null|int,
     *   gitSubmodulesConfig?: null|GitSubmodulesConfig|array,
     *   buildspec?: null|string,
     *   auth?: null|SourceAuth|array,
     *   reportBuildStatus?: null|bool,
     *   buildStatusConfig?: null|BuildStatusConfig|array,
     *   insecureSsl?: null|bool,
     *   sourceIdentifier?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? null;
        $this->location = $input['location'] ?? null;
        $this->gitCloneDepth = $input['gitCloneDepth'] ?? null;
        $this->gitSubmodulesConfig = isset($input['gitSubmodulesConfig']) ? GitSubmodulesConfig::create($input['gitSubmodulesConfig']) : null;
        $this->buildspec = $input['buildspec'] ?? null;
        $this->auth = isset($input['auth']) ? SourceAuth::create($input['auth']) : null;
        $this->reportBuildStatus = $input['reportBuildStatus'] ?? null;
        $this->buildStatusConfig = isset($input['buildStatusConfig']) ? BuildStatusConfig::create($input['buildStatusConfig']) : null;
        $this->insecureSsl = $input['insecureSsl'] ?? null;
        $this->sourceIdentifier = $input['sourceIdentifier'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAuth(): ?SourceAuth
    {
        return $this->auth;
    }

    public function getBuildStatusConfig(): ?BuildStatusConfig
    {
        return $this->buildStatusConfig;
    }

    public function getBuildspec(): ?string
    {
        return $this->buildspec;
    }

    public function getGitCloneDepth(): ?int
    {
        return $this->gitCloneDepth;
    }

    public function getGitSubmodulesConfig(): ?GitSubmodulesConfig
    {
        return $this->gitSubmodulesConfig;
    }

    public function getInsecureSsl(): ?bool
    {
        return $this->insecureSsl;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getReportBuildStatus(): ?bool
    {
        return $this->reportBuildStatus;
    }

    public function getSourceIdentifier(): ?string
    {
        return $this->sourceIdentifier;
    }

    /**
     * @return SourceType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!SourceType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "SourceType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->location) {
            $payload['location'] = $v;
        }
        if (null !== $v = $this->gitCloneDepth) {
            $payload['gitCloneDepth'] = $v;
        }
        if (null !== $v = $this->gitSubmodulesConfig) {
            $payload['gitSubmodulesConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->buildspec) {
            $payload['buildspec'] = $v;
        }
        if (null !== $v = $this->auth) {
            $payload['auth'] = $v->requestBody();
        }
        if (null !== $v = $this->reportBuildStatus) {
            $payload['reportBuildStatus'] = (bool) $v;
        }
        if (null !== $v = $this->buildStatusConfig) {
            $payload['buildStatusConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->insecureSsl) {
            $payload['insecureSsl'] = (bool) $v;
        }
        if (null !== $v = $this->sourceIdentifier) {
            $payload['sourceIdentifier'] = $v;
        }

        return $payload;
    }
}
