<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\ArtifactNamespace;
use AsyncAws\CodeBuild\Enum\ArtifactPackaging;
use AsyncAws\CodeBuild\Enum\ArtifactsType;
use AsyncAws\CodeBuild\Enum\BucketOwnerAccess;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Build output artifact settings that override, for this build only, the latest ones already defined in the build
 * project.
 */
final class ProjectArtifacts
{
    /**
     * The type of build output artifact. Valid values include:.
     */
    private $type;

    /**
     * Information about the build output artifact location:.
     */
    private $location;

    /**
     * Along with `namespaceType` and `name`, the pattern that CodeBuild uses to name and store the output artifact:.
     */
    private $path;

    /**
     * Along with `path` and `name`, the pattern that CodeBuild uses to determine the name and location to store the output
     * artifact:.
     */
    private $namespaceType;

    /**
     * Along with `path` and `namespaceType`, the pattern that CodeBuild uses to name and store the output artifact:.
     */
    private $name;

    /**
     * The type of build output artifact to create:.
     */
    private $packaging;

    /**
     * If this flag is set, a name specified in the buildspec file overrides the artifact name. The name specified in a
     * buildspec file is calculated at build time and uses the Shell Command Language. For example, you can append a date
     * and time to your artifact name so that it is always unique.
     */
    private $overrideArtifactName;

    /**
     * Set to true if you do not want your output artifacts encrypted. This option is valid only if your artifacts type is
     * Amazon S3. If this is set with another artifacts type, an invalidInputException is thrown.
     */
    private $encryptionDisabled;

    /**
     * An identifier for this artifact definition.
     */
    private $artifactIdentifier;

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
        $this->type = $input['type'] ?? null;
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
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "type" for "%s". The value cannot be null.', __CLASS__));
        }
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
}
