<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Visibility;

use AsyncAws\S3\Result\Grant as ResultGrant;
use AsyncAws\S3\ValueObject\Grant;

interface VisibilityConverter
{
    public function visibilityToAcl(string $visibility): string;

    /**
     * @param Grant[] $grants
     */
    public function aclToVisibility(array $grants): string;

    public function defaultForDirectories(): string;
}

// to remove wth release of 0.4 of async-aws/s3
if (!\class_exists(Grant::class)) {
    /** @psalm-suppress UndefinedClass */
    \class_alias(ResultGrant::class, Grant::class);
}
