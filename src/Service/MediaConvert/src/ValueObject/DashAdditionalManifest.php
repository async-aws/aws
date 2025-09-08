<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify the details for each additional DASH manifest that you want the service to generate for this output group.
 * Each manifest can reference a different subset of outputs in the group.
 */
final class DashAdditionalManifest
{
    /**
     * Specify a name modifier that the service adds to the name of this manifest to make it different from the file names
     * of the other main manifests in the output group. For example, say that the default main manifest for your DASH group
     * is film-name.mpd. If you enter "-no-premium" for this setting, then the file name the service generates for this
     * top-level manifest is film-name-no-premium.mpd.
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
     * }|DashAdditionalManifest $input
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
