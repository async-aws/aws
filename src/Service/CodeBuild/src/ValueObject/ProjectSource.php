<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\SourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the build input source code for the build project.
 */
final class ProjectSource
{
    /**
     * The type of repository that contains the source code to be built. Valid values include:
     *
     * - `BITBUCKET`: The source code is in a Bitbucket repository.
     * - `CODECOMMIT`: The source code is in an CodeCommit repository.
     * - `CODEPIPELINE`: The source code settings are specified in the source action of a pipeline in CodePipeline.
     * - `GITHUB`: The source code is in a GitHub or GitHub Enterprise Cloud repository.
     * - `GITHUB_ENTERPRISE`: The source code is in a GitHub Enterprise Server repository.
     * - `NO_SOURCE`: The project does not have input source code.
     * - `S3`: The source code is in an Amazon S3 bucket.
     *
     * @var SourceType::*
     */
    private $type;

    /**
     * Information about the location of the source code to be built. Valid values include:
     *
     * - For source code settings that are specified in the source action of a pipeline in CodePipeline, `location` should
     *   not be specified. If it is specified, CodePipeline ignores it. This is because CodePipeline uses the settings in a
     *   pipeline's source action instead of this value.
     * - For source code in an CodeCommit repository, the HTTPS clone URL to the repository that contains the source code
     *   and the buildspec file (for example,
     *   `https://git-codecommit.<region-ID>.amazonaws.com/v1/repos/<repo-name>`).
     * - For source code in an Amazon S3 input bucket, one of the following.
     *
     *   - The path to the ZIP file that contains the source code (for example,
     *     `<bucket-name>/<path>/<object-name>.zip`).
     *   - The path to the folder that contains the source code (for example,
     *     `<bucket-name>/<path-to-source-code>/<folder>/`).
     *
     * - For source code in a GitHub repository, the HTTPS clone URL to the repository that contains the source and the
     *   buildspec file. You must connect your Amazon Web Services account to your GitHub account. Use the CodeBuild console
     *   to start creating a build project. When you use the console to connect (or reconnect) with GitHub, on the GitHub
     *   **Authorize application** page, for **Organization access**, choose **Request access** next to each repository you
     *   want to allow CodeBuild to have access to, and then choose **Authorize application**. (After you have connected to
     *   your GitHub account, you do not need to finish creating the build project. You can leave the CodeBuild console.) To
     *   instruct CodeBuild to use this connection, in the `source` object, set the `auth` object's `type` value to `OAUTH`.
     * - For source code in a Bitbucket repository, the HTTPS clone URL to the repository that contains the source and the
     *   buildspec file. You must connect your Amazon Web Services account to your Bitbucket account. Use the CodeBuild
     *   console to start creating a build project. When you use the console to connect (or reconnect) with Bitbucket, on
     *   the Bitbucket **Confirm access to your account** page, choose **Grant access**. (After you have connected to your
     *   Bitbucket account, you do not need to finish creating the build project. You can leave the CodeBuild console.) To
     *   instruct CodeBuild to use this connection, in the `source` object, set the `auth` object's `type` value to `OAUTH`.
     *
     * If you specify `CODEPIPELINE` for the `Type` property, don't specify this property. For all of the other types, you
     * must specify `Location`.
     *
     * @var string|null
     */
    private $location;

    /**
     * Information about the Git clone depth for the build project.
     *
     * @var int|null
     */
    private $gitCloneDepth;

    /**
     * Information about the Git submodules configuration for the build project.
     *
     * @var GitSubmodulesConfig|null
     */
    private $gitSubmodulesConfig;

    /**
     * The buildspec file declaration to use for the builds in this build project.
     *
     * If this value is set, it can be either an inline buildspec definition, the path to an alternate buildspec file
     * relative to the value of the built-in `CODEBUILD_SRC_DIR` environment variable, or the path to an S3 bucket. The
     * bucket must be in the same Amazon Web Services Region as the build project. Specify the buildspec file using its ARN
     * (for example, `arn:aws:s3:::my-codebuild-sample2/buildspec.yml`). If this value is not provided or is set to an empty
     * string, the source code must contain a buildspec file in its root directory. For more information, see Buildspec File
     * Name and Storage Location [^1].
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec-ref-name-storage
     *
     * @var string|null
     */
    private $buildspec;

    /**
     * Information about the authorization settings for CodeBuild to access the source code to be built.
     *
     * This information is for the CodeBuild console's use only. Your code should not get or set this information directly.
     *
     * @var SourceAuth|null
     */
    private $auth;

    /**
     * Set to true to report the status of a build's start and finish to your source provider. This option is valid only
     * when your source provider is GitHub, GitHub Enterprise, or Bitbucket. If this is set and you use a different source
     * provider, an `invalidInputException` is thrown.
     *
     * To be able to report the build status to the source provider, the user associated with the source provider must have
     * write access to the repo. If the user does not have write access, the build status cannot be updated. For more
     * information, see Source provider access [^1] in the *CodeBuild User Guide*.
     *
     * The status of a build triggered by a webhook is always reported to your source provider.
     *
     * If your project's builds are triggered by a webhook, you must push a new commit to the repo for a change to this
     * property to take effect.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/access-tokens.html
     *
     * @var bool|null
     */
    private $reportBuildStatus;

    /**
     * Contains information that defines how the build project reports the build status to the source provider. This option
     * is only used when the source provider is `GITHUB`, `GITHUB_ENTERPRISE`, or `BITBUCKET`.
     *
     * @var BuildStatusConfig|null
     */
    private $buildStatusConfig;

    /**
     * Enable this flag to ignore SSL warnings while connecting to the project source code.
     *
     * @var bool|null
     */
    private $insecureSsl;

    /**
     * An identifier for this project source. The identifier can only contain alphanumeric characters and underscores, and
     * must be less than 128 characters in length.
     *
     * @var string|null
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
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
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
     * }|ProjectSource $input
     */
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
        $v = $this->type;
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
