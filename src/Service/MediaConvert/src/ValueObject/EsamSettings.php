<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for Event Signaling And Messaging (ESAM). If you don't do ad insertion, you can ignore these settings.
 */
final class EsamSettings
{
    /**
     * Specifies an ESAM ManifestConfirmConditionNotification XML as per OC-SP-ESAM-API-I03-131025. The transcoder uses the
     * manifest conditioning instructions that you provide in the setting MCC XML.
     *
     * @var EsamManifestConfirmConditionNotification|null
     */
    private $manifestConfirmConditionNotification;

    /**
     * Specifies the stream distance, in milliseconds, between the SCTE 35 messages that the transcoder places and the
     * splice points that they refer to. If the time between the start of the asset and the SCTE-35 message is less than
     * this value, then the transcoder places the SCTE-35 marker at the beginning of the stream.
     *
     * @var int|null
     */
    private $responseSignalPreroll;

    /**
     * Specifies an ESAM SignalProcessingNotification XML as per OC-SP-ESAM-API-I03-131025. The transcoder uses the signal
     * processing instructions that you provide in the setting SCC XML.
     *
     * @var EsamSignalProcessingNotification|null
     */
    private $signalProcessingNotification;

    /**
     * @param array{
     *   ManifestConfirmConditionNotification?: EsamManifestConfirmConditionNotification|array|null,
     *   ResponseSignalPreroll?: int|null,
     *   SignalProcessingNotification?: EsamSignalProcessingNotification|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->manifestConfirmConditionNotification = isset($input['ManifestConfirmConditionNotification']) ? EsamManifestConfirmConditionNotification::create($input['ManifestConfirmConditionNotification']) : null;
        $this->responseSignalPreroll = $input['ResponseSignalPreroll'] ?? null;
        $this->signalProcessingNotification = isset($input['SignalProcessingNotification']) ? EsamSignalProcessingNotification::create($input['SignalProcessingNotification']) : null;
    }

    /**
     * @param array{
     *   ManifestConfirmConditionNotification?: EsamManifestConfirmConditionNotification|array|null,
     *   ResponseSignalPreroll?: int|null,
     *   SignalProcessingNotification?: EsamSignalProcessingNotification|array|null,
     * }|EsamSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getManifestConfirmConditionNotification(): ?EsamManifestConfirmConditionNotification
    {
        return $this->manifestConfirmConditionNotification;
    }

    public function getResponseSignalPreroll(): ?int
    {
        return $this->responseSignalPreroll;
    }

    public function getSignalProcessingNotification(): ?EsamSignalProcessingNotification
    {
        return $this->signalProcessingNotification;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->manifestConfirmConditionNotification) {
            $payload['manifestConfirmConditionNotification'] = $v->requestBody();
        }
        if (null !== $v = $this->responseSignalPreroll) {
            $payload['responseSignalPreroll'] = $v;
        }
        if (null !== $v = $this->signalProcessingNotification) {
            $payload['signalProcessingNotification'] = $v->requestBody();
        }

        return $payload;
    }
}
