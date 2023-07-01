<?php

namespace AsyncAws\CodeGenerator\File\Location;

interface DirectoryResolver
{
    /**
     * Resolves the directory in which files should be written for this namespace.
     */
    public function resolveDirectory(string $namespace): string;
}
