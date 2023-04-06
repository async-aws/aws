<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\WorkGroupState;

/**
 * Information about the workgroup.
 */
final class WorkGroup
{
    /**
     * The workgroup name.
     */
    private $name;

    /**
     * The state of the workgroup: ENABLED or DISABLED.
     */
    private $state;

    /**
     * The configuration of the workgroup, which includes the location in Amazon S3 where query and calculation results are
     * stored, the encryption configuration, if any, used for query and calculation results; whether the Amazon CloudWatch
     * Metrics are enabled for the workgroup; whether workgroup settings override client-side settings; and the data usage
     * limits for the amount of data scanned per query or per workgroup. The workgroup settings override is specified in
     * `EnforceWorkGroupConfiguration` (true/false) in the `WorkGroupConfiguration`. See
     * WorkGroupConfiguration$EnforceWorkGroupConfiguration.
     */
    private $configuration;

    /**
     * The workgroup description.
     */
    private $description;

    /**
     * The date and time the workgroup was created.
     */
    private $creationTime;

    /**
     * @param array{
     *   Name: string,
     *   State?: null|WorkGroupState::*,
     *   Configuration?: null|WorkGroupConfiguration|array,
     *   Description?: null|string,
     *   CreationTime?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->configuration = isset($input['Configuration']) ? WorkGroupConfiguration::create($input['Configuration']) : null;
        $this->description = $input['Description'] ?? null;
        $this->creationTime = $input['CreationTime'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfiguration(): ?WorkGroupConfiguration
    {
        return $this->configuration;
    }

    public function getCreationTime(): ?\DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return WorkGroupState::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }
}
