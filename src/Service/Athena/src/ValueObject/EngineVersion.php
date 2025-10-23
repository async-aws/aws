<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The Athena engine version for running queries, or the PySpark engine version for running sessions.
 */
final class EngineVersion
{
    /**
     * The engine version requested by the user. Possible values are determined by the output of `ListEngineVersions`,
     * including AUTO. The default is AUTO.
     *
     * @var string|null
     */
    private $selectedEngineVersion;

    /**
     * Read only. The engine version on which the query runs. If the user requests a valid engine version other than Auto,
     * the effective engine version is the same as the engine version that the user requested. If the user requests Auto,
     * the effective engine version is chosen by Athena. When a request to update the engine version is made by a
     * `CreateWorkGroup` or `UpdateWorkGroup` operation, the `EffectiveEngineVersion` field is ignored.
     *
     * @var string|null
     */
    private $effectiveEngineVersion;

    /**
     * @param array{
     *   SelectedEngineVersion?: string|null,
     *   EffectiveEngineVersion?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->selectedEngineVersion = $input['SelectedEngineVersion'] ?? null;
        $this->effectiveEngineVersion = $input['EffectiveEngineVersion'] ?? null;
    }

    /**
     * @param array{
     *   SelectedEngineVersion?: string|null,
     *   EffectiveEngineVersion?: string|null,
     * }|EngineVersion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEffectiveEngineVersion(): ?string
    {
        return $this->effectiveEngineVersion;
    }

    public function getSelectedEngineVersion(): ?string
    {
        return $this->selectedEngineVersion;
    }
}
