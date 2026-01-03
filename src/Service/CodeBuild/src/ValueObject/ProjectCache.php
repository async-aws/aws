<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\CacheMode;
use AsyncAws\CodeBuild\Enum\CacheType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the cache for the build project.
 */
final class ProjectCache
{
    /**
     * The type of cache used by the build project. Valid values include:
     *
     * - `NO_CACHE`: The build project does not use any cache.
     * - `S3`: The build project reads and writes from and to S3.
     * - `LOCAL`: The build project stores a cache locally on a build host that is only available to that build host.
     *
     * @var CacheType::*
     */
    private $type;

    /**
     * Information about the cache location:
     *
     * - `NO_CACHE` or `LOCAL`: This value is ignored.
     * - `S3`: This is the S3 bucket name/prefix.
     *
     * @var string|null
     */
    private $location;

    /**
     * An array of strings that specify the local cache modes. You can use one or more local cache modes at the same time.
     * This is only used for `LOCAL` cache types.
     *
     * Possible values are:
     *
     * - `LOCAL_SOURCE_CACHE`:
     *
     *   Caches Git metadata for primary and secondary sources. After the cache is created, subsequent builds pull only the
     *   change between commits. This mode is a good choice for projects with a clean working directory and a source that is
     *   a large Git repository. If you choose this option and your project does not use a Git repository (GitHub, GitHub
     *   Enterprise, or Bitbucket), the option is ignored.
     * - `LOCAL_DOCKER_LAYER_CACHE`:
     *
     *   Caches existing Docker layers. This mode is a good choice for projects that build or pull large Docker images. It
     *   can prevent the performance issues caused by pulling large Docker images down from the network.
     *
     *   > - You can use a Docker layer cache in the Linux environment only.
     *   > - The `privileged` flag must be set so that your project has the required Docker permissions.
     *   > - You should consider the security implications before you use a Docker layer cache.
     *   >
     *
     * - `LOCAL_CUSTOM_CACHE`:
     *
     *   Caches directories you specify in the buildspec file. This mode is a good choice if your build scenario is not
     *   suited to one of the other three local cache modes. If you use a custom cache:
     *
     *   - Only directories can be specified for caching. You cannot specify individual files.
     *   - Symlinks are used to reference cached directories.
     *   - Cached directories are linked to your build before it downloads its project sources. Cached items are overridden
     *     if a source item has the same name. Directories are specified using cache paths in the buildspec file.
     *
     * @var list<CacheMode::*>|null
     */
    private $modes;

    /**
     * Defines the scope of the cache. You can use this namespace to share a cache across multiple projects. For more
     * information, see Cache sharing between projects [^1] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/caching-s3.html#caching-s3-sharing
     *
     * @var string|null
     */
    private $cacheNamespace;

    /**
     * @param array{
     *   type: CacheType::*,
     *   location?: string|null,
     *   modes?: array<CacheMode::*>|null,
     *   cacheNamespace?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->location = $input['location'] ?? null;
        $this->modes = $input['modes'] ?? null;
        $this->cacheNamespace = $input['cacheNamespace'] ?? null;
    }

    /**
     * @param array{
     *   type: CacheType::*,
     *   location?: string|null,
     *   modes?: array<CacheMode::*>|null,
     *   cacheNamespace?: string|null,
     * }|ProjectCache $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCacheNamespace(): ?string
    {
        return $this->cacheNamespace;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @return list<CacheMode::*>
     */
    public function getModes(): array
    {
        return $this->modes ?? [];
    }

    /**
     * @return CacheType::*
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
        if (!CacheType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "CacheType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->location) {
            $payload['location'] = $v;
        }
        if (null !== $v = $this->modes) {
            $index = -1;
            $payload['modes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!CacheMode::exists($listValue)) {
                    /** @psalm-suppress NoValue */
                    throw new InvalidArgument(\sprintf('Invalid parameter "modes" for "%s". The value "%s" is not a valid "CacheMode".', __CLASS__, $listValue));
                }
                $payload['modes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->cacheNamespace) {
            $payload['cacheNamespace'] = $v;
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
