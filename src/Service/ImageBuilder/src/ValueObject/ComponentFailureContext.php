<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains details about the component that caused the image creation process to fail. The details identify the first
 * step that failed when the component ran.
 */
final class ComponentFailureContext
{
    /**
     * The Amazon Resource Name (ARN) of the component build version that failed.
     *
     * @var string|null
     */
    private $componentArn;

    /**
     * The name of the phase in the component document where the failure occurred, such as `build`, `validate`, or `test`.
     *
     * @var string|null
     */
    private $phaseName;

    /**
     * The name of the step in the component document that failed.
     *
     * @var string|null
     */
    private $stepName;

    /**
     * The action that the failed step runs, for example `ExecuteBash`.
     *
     * @var string|null
     */
    private $action;

    /**
     * The error message from the step that failed. Image Builder truncates messages that are longer than 1024 characters.
     * The component log in Amazon CloudWatch Logs contains the full output.
     *
     * @var string|null
     */
    private $errorMessage;

    /**
     * @param array{
     *   componentArn?: string|null,
     *   phaseName?: string|null,
     *   stepName?: string|null,
     *   action?: string|null,
     *   errorMessage?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->componentArn = $input['componentArn'] ?? null;
        $this->phaseName = $input['phaseName'] ?? null;
        $this->stepName = $input['stepName'] ?? null;
        $this->action = $input['action'] ?? null;
        $this->errorMessage = $input['errorMessage'] ?? null;
    }

    /**
     * @param array{
     *   componentArn?: string|null,
     *   phaseName?: string|null,
     *   stepName?: string|null,
     *   action?: string|null,
     *   errorMessage?: string|null,
     * }|ComponentFailureContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getComponentArn(): ?string
    {
        return $this->componentArn;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getPhaseName(): ?string
    {
        return $this->phaseName;
    }

    public function getStepName(): ?string
    {
        return $this->stepName;
    }
}
