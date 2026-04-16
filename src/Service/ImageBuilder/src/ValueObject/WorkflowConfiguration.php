<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\ImageBuilder\Enum\OnWorkflowFailure;

/**
 * Contains control settings and configurable inputs for a workflow resource.
 */
final class WorkflowConfiguration
{
    /**
     * The Amazon Resource Name (ARN) of the workflow resource.
     *
     * @var string
     */
    private $workflowArn;

    /**
     * Contains parameter values for each of the parameters that the workflow document defined for the workflow resource.
     *
     * @var WorkflowParameter[]|null
     */
    private $parameters;

    /**
     * Test workflows are defined within named runtime groups called parallel groups. The parallel group is the named group
     * that contains this test workflow. Test workflows within a parallel group can run at the same time. Image Builder
     * starts up to five test workflows in the group at the same time, and starts additional workflows as others complete,
     * until all workflows in the group have completed. This field only applies for test workflows.
     *
     * @var string|null
     */
    private $parallelGroup;

    /**
     * The action to take if the workflow fails.
     *
     * @var OnWorkflowFailure::*|null
     */
    private $onFailure;

    /**
     * @param array{
     *   workflowArn: string,
     *   parameters?: array<WorkflowParameter|array>|null,
     *   parallelGroup?: string|null,
     *   onFailure?: OnWorkflowFailure::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->workflowArn = $input['workflowArn'] ?? $this->throwException(new InvalidArgument('Missing required field "workflowArn".'));
        $this->parameters = isset($input['parameters']) ? array_map([WorkflowParameter::class, 'create'], $input['parameters']) : null;
        $this->parallelGroup = $input['parallelGroup'] ?? null;
        $this->onFailure = $input['onFailure'] ?? null;
    }

    /**
     * @param array{
     *   workflowArn: string,
     *   parameters?: array<WorkflowParameter|array>|null,
     *   parallelGroup?: string|null,
     *   onFailure?: OnWorkflowFailure::*|null,
     * }|WorkflowConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return OnWorkflowFailure::*|null
     */
    public function getOnFailure(): ?string
    {
        return $this->onFailure;
    }

    public function getParallelGroup(): ?string
    {
        return $this->parallelGroup;
    }

    /**
     * @return WorkflowParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    public function getWorkflowArn(): string
    {
        return $this->workflowArn;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
