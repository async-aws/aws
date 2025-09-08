<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Group of outputs.
 */
final class OutputGroup
{
    /**
     * Use automated encoding to have MediaConvert choose your encoding settings for you, based on characteristics of your
     * input video.
     *
     * @var AutomatedEncodingSettings|null
     */
    private $automatedEncodingSettings;

    /**
     * Use Custom Group Name to specify a name for the output group. This value is displayed on the console and can make
     * your job settings JSON more human-readable. It does not affect your outputs. Use up to twelve characters that are
     * either letters, numbers, spaces, or underscores.
     *
     * @var string|null
     */
    private $customName;

    /**
     * Name of the output group.
     *
     * @var string|null
     */
    private $name;

    /**
     * Output Group settings, including type.
     *
     * @var OutputGroupSettings|null
     */
    private $outputGroupSettings;

    /**
     * This object holds groups of encoding settings, one group of settings per output.
     *
     * @var Output[]|null
     */
    private $outputs;

    /**
     * @param array{
     *   AutomatedEncodingSettings?: AutomatedEncodingSettings|array|null,
     *   CustomName?: string|null,
     *   Name?: string|null,
     *   OutputGroupSettings?: OutputGroupSettings|array|null,
     *   Outputs?: array<Output|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->automatedEncodingSettings = isset($input['AutomatedEncodingSettings']) ? AutomatedEncodingSettings::create($input['AutomatedEncodingSettings']) : null;
        $this->customName = $input['CustomName'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->outputGroupSettings = isset($input['OutputGroupSettings']) ? OutputGroupSettings::create($input['OutputGroupSettings']) : null;
        $this->outputs = isset($input['Outputs']) ? array_map([Output::class, 'create'], $input['Outputs']) : null;
    }

    /**
     * @param array{
     *   AutomatedEncodingSettings?: AutomatedEncodingSettings|array|null,
     *   CustomName?: string|null,
     *   Name?: string|null,
     *   OutputGroupSettings?: OutputGroupSettings|array|null,
     *   Outputs?: array<Output|array>|null,
     * }|OutputGroup $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAutomatedEncodingSettings(): ?AutomatedEncodingSettings
    {
        return $this->automatedEncodingSettings;
    }

    public function getCustomName(): ?string
    {
        return $this->customName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOutputGroupSettings(): ?OutputGroupSettings
    {
        return $this->outputGroupSettings;
    }

    /**
     * @return Output[]
     */
    public function getOutputs(): array
    {
        return $this->outputs ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->automatedEncodingSettings) {
            $payload['automatedEncodingSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->customName) {
            $payload['customName'] = $v;
        }
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }
        if (null !== $v = $this->outputGroupSettings) {
            $payload['outputGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->outputs) {
            $index = -1;
            $payload['outputs'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['outputs'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
