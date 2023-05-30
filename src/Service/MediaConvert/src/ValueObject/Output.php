<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Each output in your job is a collection of settings that describes how you want MediaConvert to encode a single
 * output file or stream. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/create-outputs.html.
 */
final class Output
{
    /**
     * (AudioDescriptions) contains groups of audio encoding settings organized by audio codec. Include one instance of
     * (AudioDescriptions) per output. (AudioDescriptions) can contain multiple groups of encoding settings.
     */
    private $audioDescriptions;

    /**
     * (CaptionDescriptions) contains groups of captions settings. For each output that has captions, include one instance
     * of (CaptionDescriptions). (CaptionDescriptions) can contain multiple groups of captions settings.
     */
    private $captionDescriptions;

    /**
     * Container specific settings.
     */
    private $containerSettings;

    /**
     * Use Extension (Extension) to specify the file extension for outputs in File output groups. If you do not specify a
     * value, the service will use default extensions by container type as follows * MPEG-2 transport stream, m2ts *
     * Quicktime, mov * MXF container, mxf * MPEG-4 container, mp4 * WebM container, webm * No Container, the service will
     * use codec extensions (e.g. AAC, H265, H265, AC3).
     */
    private $extension;

    /**
     * Use Name modifier (NameModifier) to have the service add a string to the end of each output filename. You specify the
     * base filename as part of your destination URI. When you create multiple outputs in the same output group, Name
     * modifier (NameModifier) is required. Name modifier also accepts format identifiers. For DASH ISO outputs, if you use
     * the format identifiers $Number$ or $Time$ in one output, you must use them in the same way in all outputs of the
     * output group.
     */
    private $nameModifier;

    /**
     * Specific settings for this type of output.
     */
    private $outputSettings;

    /**
     * Use Preset (Preset) to specify a preset for your transcoding settings. Provide the system or custom preset name. You
     * can specify either Preset (Preset) or Container settings (ContainerSettings), but not both.
     */
    private $preset;

    /**
     * VideoDescription contains a group of video encoding settings. The specific video settings depend on the video codec
     * that you choose for the property codec. Include one instance of VideoDescription per output.
     */
    private $videoDescription;

    /**
     * @param array{
     *   AudioDescriptions?: null|AudioDescription[],
     *   CaptionDescriptions?: null|CaptionDescription[],
     *   ContainerSettings?: null|ContainerSettings|array,
     *   Extension?: null|string,
     *   NameModifier?: null|string,
     *   OutputSettings?: null|OutputSettings|array,
     *   Preset?: null|string,
     *   VideoDescription?: null|VideoDescription|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDescriptions = isset($input['AudioDescriptions']) ? array_map([AudioDescription::class, 'create'], $input['AudioDescriptions']) : null;
        $this->captionDescriptions = isset($input['CaptionDescriptions']) ? array_map([CaptionDescription::class, 'create'], $input['CaptionDescriptions']) : null;
        $this->containerSettings = isset($input['ContainerSettings']) ? ContainerSettings::create($input['ContainerSettings']) : null;
        $this->extension = $input['Extension'] ?? null;
        $this->nameModifier = $input['NameModifier'] ?? null;
        $this->outputSettings = isset($input['OutputSettings']) ? OutputSettings::create($input['OutputSettings']) : null;
        $this->preset = $input['Preset'] ?? null;
        $this->videoDescription = isset($input['VideoDescription']) ? VideoDescription::create($input['VideoDescription']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AudioDescription[]
     */
    public function getAudioDescriptions(): array
    {
        return $this->audioDescriptions ?? [];
    }

    /**
     * @return CaptionDescription[]
     */
    public function getCaptionDescriptions(): array
    {
        return $this->captionDescriptions ?? [];
    }

    public function getContainerSettings(): ?ContainerSettings
    {
        return $this->containerSettings;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function getNameModifier(): ?string
    {
        return $this->nameModifier;
    }

    public function getOutputSettings(): ?OutputSettings
    {
        return $this->outputSettings;
    }

    public function getPreset(): ?string
    {
        return $this->preset;
    }

    public function getVideoDescription(): ?VideoDescription
    {
        return $this->videoDescription;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDescriptions) {
            $index = -1;
            $payload['audioDescriptions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['audioDescriptions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->captionDescriptions) {
            $index = -1;
            $payload['captionDescriptions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['captionDescriptions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->containerSettings) {
            $payload['containerSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->extension) {
            $payload['extension'] = $v;
        }
        if (null !== $v = $this->nameModifier) {
            $payload['nameModifier'] = $v;
        }
        if (null !== $v = $this->outputSettings) {
            $payload['outputSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->preset) {
            $payload['preset'] = $v;
        }
        if (null !== $v = $this->videoDescription) {
            $payload['videoDescription'] = $v->requestBody();
        }

        return $payload;
    }
}
