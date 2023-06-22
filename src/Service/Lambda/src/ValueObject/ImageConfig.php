<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Configuration values that override the container image Dockerfile settings. For more information, see Container image
 * settings [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/images-create.html#images-parms
 */
final class ImageConfig
{
    /**
     * Specifies the entry point to their application, which is typically the location of the runtime executable.
     */
    private $entryPoint;

    /**
     * Specifies parameters that you want to pass in with ENTRYPOINT.
     */
    private $command;

    /**
     * Specifies the working directory.
     */
    private $workingDirectory;

    /**
     * @param array{
     *   EntryPoint?: null|string[],
     *   Command?: null|string[],
     *   WorkingDirectory?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->entryPoint = $input['EntryPoint'] ?? null;
        $this->command = $input['Command'] ?? null;
        $this->workingDirectory = $input['WorkingDirectory'] ?? null;
    }

    /**
     * @param array{
     *   EntryPoint?: null|string[],
     *   Command?: null|string[],
     *   WorkingDirectory?: null|string,
     * }|ImageConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getCommand(): array
    {
        return $this->command ?? [];
    }

    /**
     * @return string[]
     */
    public function getEntryPoint(): array
    {
        return $this->entryPoint ?? [];
    }

    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }
}
