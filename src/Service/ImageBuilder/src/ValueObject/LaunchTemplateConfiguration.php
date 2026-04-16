<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Identifies an Amazon EC2 launch template to use for a specific account.
 */
final class LaunchTemplateConfiguration
{
    /**
     * Identifies the Amazon EC2 launch template to use.
     *
     * @var string
     */
    private $launchTemplateId;

    /**
     * The account ID that this configuration applies to.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * Set the specified Amazon EC2 launch template as the default launch template for the specified account.
     *
     * @var bool|null
     */
    private $setDefaultVersion;

    /**
     * @param array{
     *   launchTemplateId: string,
     *   accountId?: string|null,
     *   setDefaultVersion?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->launchTemplateId = $input['launchTemplateId'] ?? $this->throwException(new InvalidArgument('Missing required field "launchTemplateId".'));
        $this->accountId = $input['accountId'] ?? null;
        $this->setDefaultVersion = $input['setDefaultVersion'] ?? null;
    }

    /**
     * @param array{
     *   launchTemplateId: string,
     *   accountId?: string|null,
     *   setDefaultVersion?: bool|null,
     * }|LaunchTemplateConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getLaunchTemplateId(): string
    {
        return $this->launchTemplateId;
    }

    public function getSetDefaultVersion(): ?bool
    {
        return $this->setDefaultVersion;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
