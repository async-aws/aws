<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\WorkGroupState;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A workgroup, which contains a name, description, creation time, state, and other configuration, listed under
 * WorkGroup$Configuration. Each workgroup enables you to isolate queries for you or your group of users from other
 * queries in the same account, to configure the query results location and the encryption configuration (known as
 * workgroup settings), to enable sending query metrics to Amazon CloudWatch, and to establish per-query data usage
 * control limits for all queries in a workgroup. The workgroup settings override is specified in
 * `EnforceWorkGroupConfiguration` (true/false) in the `WorkGroupConfiguration`. See
 * WorkGroupConfiguration$EnforceWorkGroupConfiguration.
 */
final class WorkGroup
{
    /**
     * The workgroup name.
     *
     * @var string
     */
    private $name;

    /**
     * The state of the workgroup: ENABLED or DISABLED.
     *
     * @var WorkGroupState::*|null
     */
    private $state;

    /**
     * The configuration of the workgroup, which includes the location in Amazon S3 where query and calculation results are
     * stored, the encryption configuration, if any, used for query and calculation results; whether the Amazon CloudWatch
     * Metrics are enabled for the workgroup; whether workgroup settings override client-side settings; and the data usage
     * limits for the amount of data scanned per query or per workgroup. The workgroup settings override is specified in
     * `EnforceWorkGroupConfiguration` (true/false) in the `WorkGroupConfiguration`. See
     * WorkGroupConfiguration$EnforceWorkGroupConfiguration.
     *
     * @var WorkGroupConfiguration|null
     */
    private $configuration;

    /**
     * The workgroup description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The date and time the workgroup was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationTime;

    /**
     * The ARN of the IAM Identity Center enabled application associated with the workgroup.
     *
     * @var string|null
     */
    private $identityCenterApplicationArn;

    /**
     * @param array{
     *   Name: string,
     *   State?: WorkGroupState::*|null,
     *   Configuration?: WorkGroupConfiguration|array|null,
     *   Description?: string|null,
     *   CreationTime?: \DateTimeImmutable|null,
     *   IdentityCenterApplicationArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->state = $input['State'] ?? null;
        $this->configuration = isset($input['Configuration']) ? WorkGroupConfiguration::create($input['Configuration']) : null;
        $this->description = $input['Description'] ?? null;
        $this->creationTime = $input['CreationTime'] ?? null;
        $this->identityCenterApplicationArn = $input['IdentityCenterApplicationArn'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   State?: WorkGroupState::*|null,
     *   Configuration?: WorkGroupConfiguration|array|null,
     *   Description?: string|null,
     *   CreationTime?: \DateTimeImmutable|null,
     *   IdentityCenterApplicationArn?: string|null,
     * }|WorkGroup $input
     */
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

    public function getIdentityCenterApplicationArn(): ?string
    {
        return $this->identityCenterApplicationArn;
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
