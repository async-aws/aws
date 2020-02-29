<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Visibility;

use AsyncAws\S3\Result\Grant;

interface VisibilityConverter
{
    public function visibilityToAcl(string $visibility): string;

    /**
     * @param Grant[] $grants
     */
    public function aclToVisibility(array $grants): string;

    public function defaultForDirectories(): string;
}
