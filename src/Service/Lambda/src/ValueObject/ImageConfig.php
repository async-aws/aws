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
     *
     * @var string[]|null
     */
    private $entryPoint;

    /**
     * Specifies parameters that you want to pass in with ENTRYPOINT.
     *
     * @var string[]|null
     */
    private $command;

    /**
     * Specifies the working directory.
     *
     * @var string|null
     */
    private $workingDirectory;

    /**
     * @param array{
     *   EntryPoint?: string[]|null,
     *   Command?: string[]|null,
     *   WorkingDirectory?: string|null,
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
     *   EntryPoint?: string[]|null,
     *   Command?: string[]|null,
     *   WorkingDirectory?: string|null,
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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->entryPoint) {
            $index = -1;
            $payload['EntryPoint'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['EntryPoint'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->command) {
            $index = -1;
            $payload['Command'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Command'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->workingDirectory) {
            $payload['WorkingDirectory'] = $v;
        }

        return $payload;
    }
}
