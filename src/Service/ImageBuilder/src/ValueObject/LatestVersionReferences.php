<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * The resource ARNs with different wildcard variations of semantic versioning.
 */
final class LatestVersionReferences
{
    /**
     * The latest version Amazon Resource Name (ARN) of the Image Builder resource.
     *
     * @var string|null
     */
    private $latestVersionArn;

    /**
     * The latest version Amazon Resource Name (ARN) with the same `major` version of the Image Builder resource.
     *
     * @var string|null
     */
    private $latestMajorVersionArn;

    /**
     * The latest version Amazon Resource Name (ARN) with the same `minor` version of the Image Builder resource.
     *
     * @var string|null
     */
    private $latestMinorVersionArn;

    /**
     * The latest version Amazon Resource Name (ARN) with the same `patch` version of the Image Builder resource.
     *
     * @var string|null
     */
    private $latestPatchVersionArn;

    /**
     * @param array{
     *   latestVersionArn?: string|null,
     *   latestMajorVersionArn?: string|null,
     *   latestMinorVersionArn?: string|null,
     *   latestPatchVersionArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->latestVersionArn = $input['latestVersionArn'] ?? null;
        $this->latestMajorVersionArn = $input['latestMajorVersionArn'] ?? null;
        $this->latestMinorVersionArn = $input['latestMinorVersionArn'] ?? null;
        $this->latestPatchVersionArn = $input['latestPatchVersionArn'] ?? null;
    }

    /**
     * @param array{
     *   latestVersionArn?: string|null,
     *   latestMajorVersionArn?: string|null,
     *   latestMinorVersionArn?: string|null,
     *   latestPatchVersionArn?: string|null,
     * }|LatestVersionReferences $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLatestMajorVersionArn(): ?string
    {
        return $this->latestMajorVersionArn;
    }

    public function getLatestMinorVersionArn(): ?string
    {
        return $this->latestMinorVersionArn;
    }

    public function getLatestPatchVersionArn(): ?string
    {
        return $this->latestPatchVersionArn;
    }

    public function getLatestVersionArn(): ?string
    {
        return $this->latestVersionArn;
    }
}
