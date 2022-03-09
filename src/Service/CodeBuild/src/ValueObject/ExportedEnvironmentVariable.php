<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information about an exported environment variable.
 * Exported environment variables are used in conjunction with CodePipeline to export environment variables from the
 * current build stage to subsequent stages in the pipeline. For more information, see Working with variables in the
 * *CodePipeline User Guide*.
 *
 * > During a build, the value of a variable is available starting with the `install` phase. It can be updated between
 * > the start of the `install` phase and the end of the `post_build` phase. After the `post_build` phase ends, the
 * > value of exported variables cannot change.
 *
 * @see https://docs.aws.amazon.com/codepipeline/latest/userguide/actions-variables.html
 */
final class ExportedEnvironmentVariable
{
    /**
     * The name of the exported environment variable.
     */
    private $name;

    /**
     * The value assigned to the exported environment variable.
     */
    private $value;

    /**
     * @param array{
     *   name?: null|string,
     *   value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->value = $input['value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
