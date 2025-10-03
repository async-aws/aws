<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify the details for each pair of HLS and DASH additional manifests that you want the service to generate for this
 * CMAF output group. Each pair of manifests can reference a different subset of outputs in the group.
 */
final class CmafAdditionalManifest
{
    /**
     * Specify a name modifier that the service adds to the name of this manifest to make it different from the file names
     * of the other main manifests in the output group. For example, say that the default main manifest for your HLS group
     * is film-name.m3u8. If you enter "-no-premium" for this setting, then the file name the service generates for this
     * top-level manifest is film-name-no-premium.m3u8. For HLS output groups, specify a manifestNameModifier that is
     * different from the nameModifier of the output. The service uses the output name modifier to create unique names for
     * the individual variant manifests.
     *
     * @var string|null
     */
    private $manifestNameModifier;

    /**
     * Specify the outputs that you want this additional top-level manifest to reference.
     *
     * @var string[]|null
     */
    private $selectedOutputs;

    /**
     * @param array{
     *   ManifestNameModifier?: string|null,
     *   SelectedOutputs?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->manifestNameModifier = $input['ManifestNameModifier'] ?? null;
        $this->selectedOutputs = $input['SelectedOutputs'] ?? null;
    }

    /**
     * @param array{
     *   ManifestNameModifier?: string|null,
     *   SelectedOutputs?: string[]|null,
     * }|CmafAdditionalManifest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getManifestNameModifier(): ?string
    {
        return $this->manifestNameModifier;
    }

    /**
     * @return string[]
     */
    public function getSelectedOutputs(): array
    {
        return $this->selectedOutputs ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->manifestNameModifier) {
            $payload['manifestNameModifier'] = $v;
        }
        if (null !== $v = $this->selectedOutputs) {
            $index = -1;
            $payload['selectedOutputs'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['selectedOutputs'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
