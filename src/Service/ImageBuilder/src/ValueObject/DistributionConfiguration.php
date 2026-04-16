<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A distribution configuration.
 */
final class DistributionConfiguration
{
    /**
     * The Amazon Resource Name (ARN) of the distribution configuration.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The name of the distribution configuration.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the distribution configuration.
     *
     * @var string|null
     */
    private $description;

    /**
     * The distribution objects that apply Region-specific settings for the deployment of the image to targeted Regions.
     *
     * @var Distribution[]|null
     */
    private $distributions;

    /**
     * The maximum duration in minutes for this distribution configuration.
     *
     * @var int
     */
    private $timeoutMinutes;

    /**
     * The date on which this distribution configuration was created.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * The date on which this distribution configuration was last updated.
     *
     * @var string|null
     */
    private $dateUpdated;

    /**
     * The tags of the distribution configuration.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   distributions?: array<Distribution|array>|null,
     *   timeoutMinutes: int,
     *   dateCreated?: string|null,
     *   dateUpdated?: string|null,
     *   tags?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->distributions = isset($input['distributions']) ? array_map([Distribution::class, 'create'], $input['distributions']) : null;
        $this->timeoutMinutes = $input['timeoutMinutes'] ?? $this->throwException(new InvalidArgument('Missing required field "timeoutMinutes".'));
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->dateUpdated = $input['dateUpdated'] ?? null;
        $this->tags = $input['tags'] ?? null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   distributions?: array<Distribution|array>|null,
     *   timeoutMinutes: int,
     *   dateCreated?: string|null,
     *   dateUpdated?: string|null,
     *   tags?: array<string, string>|null,
     * }|DistributionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDateUpdated(): ?string
    {
        return $this->dateUpdated;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Distribution[]
     */
    public function getDistributions(): array
    {
        return $this->distributions ?? [];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getTimeoutMinutes(): int
    {
        return $this->timeoutMinutes;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
