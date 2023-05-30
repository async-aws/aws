<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\BucketOwnerAccess;

/**
 * Information about build output artifacts.
 */
final class BuildArtifacts
{
    /**
     * Information about the location of the build artifacts.
     */
    private $location;

    /**
     * The SHA-256 hash of the build artifact.
     *
     * You can use this hash along with a checksum tool to confirm file integrity and authenticity.
     *
     * > This value is available only if the build project's `packaging` value is set to `ZIP`.
     */
    private $sha256sum;

    /**
     * The MD5 hash of the build artifact.
     *
     * You can use this hash along with a checksum tool to confirm file integrity and authenticity.
     *
     * > This value is available only if the build project's `packaging` value is set to `ZIP`.
     */
    private $md5sum;

    /**
     * If this flag is set, a name specified in the buildspec file overrides the artifact name. The name specified in a
     * buildspec file is calculated at build time and uses the Shell Command Language. For example, you can append a date
     * and time to your artifact name so that it is always unique.
     */
    private $overrideArtifactName;

    /**
     * Information that tells you if encryption for build artifacts is disabled.
     */
    private $encryptionDisabled;

    /**
     * An identifier for this artifact definition.
     */
    private $artifactIdentifier;

    private $bucketOwnerAccess;

    /**
     * @param array{
     *   location?: null|string,
     *   sha256sum?: null|string,
     *   md5sum?: null|string,
     *   overrideArtifactName?: null|bool,
     *   encryptionDisabled?: null|bool,
     *   artifactIdentifier?: null|string,
     *   bucketOwnerAccess?: null|BucketOwnerAccess::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->location = $input['location'] ?? null;
        $this->sha256sum = $input['sha256sum'] ?? null;
        $this->md5sum = $input['md5sum'] ?? null;
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

    public function getMd5sum(): ?string
    {
        return $this->md5sum;
    }

    public function getOverrideArtifactName(): ?bool
    {
        return $this->overrideArtifactName;
    }

    public function getSha256sum(): ?string
    {
        return $this->sha256sum;
    }
}
