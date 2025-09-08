<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Information about the compute fleet of the build project. For more information, see Working with reserved capacity in
 * CodeBuild [^1].
 *
 * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/fleets.html
 */
final class ProjectFleet
{
    /**
     * Specifies the compute fleet ARN for the build project.
     *
     * @var string|null
     */
    private $fleetArn;

    /**
     * @param array{
     *   fleetArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fleetArn = $input['fleetArn'] ?? null;
    }

    /**
     * @param array{
     *   fleetArn?: string|null,
     * }|ProjectFleet $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFleetArn(): ?string
    {
        return $this->fleetArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->fleetArn) {
            $payload['fleetArn'] = $v;
        }

        return $payload;
    }
}
