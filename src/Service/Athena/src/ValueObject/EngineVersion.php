<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The engine version that executed the query.
 */
final class EngineVersion
{
    /**
     * The engine version requested by the user. Possible values are determined by the output of `ListEngineVersions`,
     * including Auto. The default is Auto.
     */
    private $selectedEngineVersion;

    /**
     * Read only. The engine version on which the query runs. If the user requests a valid engine version other than Auto,
     * the effective engine version is the same as the engine version that the user requested. If the user requests Auto,
     * the effective engine version is chosen by Athena. When a request to update the engine version is made by a
     * `CreateWorkGroup` or `UpdateWorkGroup` operation, the `EffectiveEngineVersion` field is ignored.
     */
    private $effectiveEngineVersion;

    /**
     * @param array{
     *   SelectedEngineVersion?: null|string,
     *   EffectiveEngineVersion?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->selectedEngineVersion = $input['SelectedEngineVersion'] ?? null;
        $this->effectiveEngineVersion = $input['EffectiveEngineVersion'] ?? null;
    }

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
