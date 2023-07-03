<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\ArtifactNamespace;
use AsyncAws\CodeBuild\Enum\ArtifactPackaging;
use AsyncAws\CodeBuild\Enum\ArtifactsType;
use AsyncAws\CodeBuild\Enum\BucketOwnerAccess;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the build output artifacts for the build project.
 */
final class ProjectArtifacts
{
    /**
     * The type of build output artifact. Valid values include:.
     *
     * - `CODEPIPELINE`: The build project has build output generated through CodePipeline.
     *
     *   > The `CODEPIPELINE` type is not supported for `secondaryArtifacts`.
     *
     * - `NO_ARTIFACTS`: The build project does not produce any build output.
     * - `S3`: The build project stores build output in Amazon S3.
     *
     * @var ArtifactsType::*
     */
    private $type;

    /**
     * Information about the build output artifact location:.
     *
     * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
     *   manages its build output locations instead of CodeBuild.
     * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
     * - If `type` is set to `S3`, this is the name of the output bucket.
     *
     * @var string|null
     */
    private $location;

    /**
     * Along with `namespaceType` and `name`, the pattern that CodeBuild uses to name and store the output artifact:.
     *
     * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
     *   manages its build output names instead of CodeBuild.
     * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
     * - If `type` is set to `S3`, this is the path to the output artifact. If `path` is not specified, `path` is not used.
     *
     * For example, if `path` is set to `MyArtifacts`, `namespaceType` is set to `NONE`, and `name` is set to
     * `MyArtifact.zip`, the output artifact is stored in the output bucket at `MyArtifacts/MyArtifact.zip`.
     *
     * @var string|null
     */
    private $path;

    /**
     * Along with `path` and `name`, the pattern that CodeBuild uses to determine the name and location to store the output
     * artifact:.
     *
     * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
     *   manages its build output names instead of CodeBuild.
     * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
     * - If `type` is set to `S3`, valid values include:
     *
     *   - `BUILD_ID`: Include the build ID in the location of the build output artifact.
     *   - `NONE`: Do not include the build ID. This is the default if `namespaceType` is not specified.
     *
     *
     * For example, if `path` is set to `MyArtifacts`, `namespaceType` is set to `BUILD_ID`, and `name` is set to
     * `MyArtifact.zip`, the output artifact is stored in `MyArtifacts/<build-ID>/MyArtifact.zip`.
     *
     * @var ArtifactNamespace::*|null
     */
    private $namespaceType;

    /**
     * Along with `path` and `namespaceType`, the pattern that CodeBuild uses to name and store the output artifact:.
     *
     * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
     *   manages its build output names instead of CodeBuild.
     * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
     * - If `type` is set to `S3`, this is the name of the output artifact object. If you set the name to be a forward slash
     *   ("/"), the artifact is stored in the root of the output bucket.
     *
     * For example:
     *
     * - If `path` is set to `MyArtifacts`, `namespaceType` is set to `BUILD_ID`, and `name` is set to `MyArtifact.zip`,
     *   then the output artifact is stored in `MyArtifacts/<build-ID>/MyArtifact.zip`.
     * - If `path` is empty, `namespaceType` is set to `NONE`, and `name` is set to "`/`", the output artifact is stored in
     *   the root of the output bucket.
     * - If `path` is set to `MyArtifacts`, `namespaceType` is set to `BUILD_ID`, and `name` is set to "`/`", the output
     *   artifact is stored in `MyArtifacts/<build-ID>`.
     *
     * @var string|null
     */
    private $name;

    /**
     * The type of build output artifact to create:.
     *
     * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
     *   manages its build output artifacts instead of CodeBuild.
     * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
     * - If `type` is set to `S3`, valid values include:
     *
     *   - `NONE`: CodeBuild creates in the output bucket a folder that contains the build output. This is the default if
     *     `packaging` is not specified.
     *   - `ZIP`: CodeBuild creates in the output bucket a ZIP file that contains the build output.
     *
     * @var ArtifactPackaging::*|null
     */
    private $packaging;

    /**
     * If this flag is set, a name specified in the buildspec file overrides the artifact name. The name specified in a
     * buildspec file is calculated at build time and uses the Shell Command Language. For example, you can append a date
     * and time to your artifact name so that it is always unique.
     *
     * @var bool|null
     */
    private $overrideArtifactName;

    /**
     * Set to true if you do not want your output artifacts encrypted. This option is valid only if your artifacts type is
     * Amazon S3. If this is set with another artifacts type, an invalidInputException is thrown.
     *
     * @var bool|null
     */
    private $encryptionDisabled;

    /**
     * An identifier for this artifact definition.
     *
     * @var string|null
     */
    private $artifactIdentifier;

    /**
     * @var BucketOwnerAccess::*|null
     */
    private $bucketOwnerAccess;

    /**
     * @param array{
     *   type: ArtifactsType::*,
     *   location?: null|string,
     *   path?: null|string,
     *   namespaceType?: null|ArtifactNamespace::*,
     *   name?: null|string,
     *   packaging?: null|ArtifactPackaging::*,
     *   overrideArtifactName?: null|bool,
     *   encryptionDisabled?: null|bool,
     *   artifactIdentifier?: null|string,
     *   bucketOwnerAccess?: null|BucketOwnerAccess::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->location = $input['location'] ?? null;
        $this->path = $input['path'] ?? null;
        $this->namespaceType = $input['namespaceType'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->packaging = $input['packaging'] ?? null;
        $this->overrideArtifactName = $input['overrideArtifactName'] ?? null;
        $this->encryptionDisabled = $input['encryptionDisabled'] ?? null;
        $this->artifactIdentifier = $input['artifactIdentifier'] ?? null;
        $this->bucketOwnerAccess = $input['bucketOwnerAccess'] ?? null;
    }

    /**
     * @param array{
     *   type: ArtifactsType::*,
     *   location?: null|string,
     *   path?: null|string,
     *   namespaceType?: null|ArtifactNamespace::*,
     *   name?: null|string,
     *   packaging?: null|ArtifactPackaging::*,
     *   overrideArtifactName?: null|bool,
     *   encryptionDisabled?: null|bool,
     *   artifactIdentifier?: null|string,
     *   bucketOwnerAccess?: null|BucketOwnerAccess::*,
     * }|ProjectArtifacts $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArtifactIdentifier(): ?string
    {
        return $this->artifactIdentifier;
    }

    /**
     * @return BucketOwnerAccess::*|null
     */
    public function getBucketOwnerAccess(): ?string
    {
        return $this->bucketOwnerAccess;
    }

    public function getEncryptionDisabled(): ?bool
    {
        return $this->encryptionDisabled;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ArtifactNamespace::*|null
     */
    public function getNamespaceType(): ?string
    {
        return $this->namespaceType;
    }

    public function getOverrideArtifactName(): ?bool
    {
        return $this->overrideArtifactName;
    }

    /**
     * @return ArtifactPackaging::*|null
     */
    public function getPackaging(): ?string
    {
        return $this->packaging;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return ArtifactsType::*
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
        if (!ArtifactsType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "ArtifactsType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->location) {
            $payload['location'] = $v;
        }
        if (null !== $v = $this->path) {
            $payload['path'] = $v;
        }
        if (null !== $v = $this->namespaceType) {
            if (!ArtifactNamespace::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "namespaceType" for "%s". The value "%s" is not a valid "ArtifactNamespace".', __CLASS__, $v));
            }
            $payload['namespaceType'] = $v;
        }
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }
        if (null !== $v = $this->packaging) {
            if (!ArtifactPackaging::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "packaging" for "%s". The value "%s" is not a valid "ArtifactPackaging".', __CLASS__, $v));
            }
            $payload['packaging'] = $v;
        }
        if (null !== $v = $this->overrideArtifactName) {
            $payload['overrideArtifactName'] = (bool) $v;
        }
        if (null !== $v = $this->encryptionDisabled) {
            $payload['encryptionDisabled'] = (bool) $v;
        }
        if (null !== $v = $this->artifactIdentifier) {
            $payload['artifactIdentifier'] = $v;
        }
        if (null !== $v = $this->bucketOwnerAccess) {
            if (!BucketOwnerAccess::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bucketOwnerAccess" for "%s". The value "%s" is not a valid "BucketOwnerAccess".', __CLASS__, $v));
            }
            $payload['bucketOwnerAccess'] = $v;
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
