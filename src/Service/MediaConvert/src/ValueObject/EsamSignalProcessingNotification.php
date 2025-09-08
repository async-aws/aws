<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * ESAM SignalProcessingNotification data defined by OC-SP-ESAM-API-I03-131025.
 */
final class EsamSignalProcessingNotification
{
    /**
     * Provide your ESAM SignalProcessingNotification XML document inside your JSON job settings. Form the XML document as
     * per OC-SP-ESAM-API-I03-131025. The transcoder will use the signal processing instructions in the message that you
     * supply. For your MPEG2-TS file outputs, if you want the service to place SCTE-35 markers at the insertion points you
     * specify in the XML document, you must also enable SCTE-35 ESAM. Note that you can either specify an ESAM XML document
     * or enable SCTE-35 passthrough. You can't do both.
     *
     * @var string|null
     */
    private $sccXml;

    /**
     * @param array{
     *   SccXml?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sccXml = $input['SccXml'] ?? null;
    }

    /**
     * @param array{
     *   SccXml?: string|null,
     * }|EsamSignalProcessingNotification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSccXml(): ?string
    {
        return $this->sccXml;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->sccXml) {
            $payload['sccXml'] = $v;
        }

        return $payload;
    }
}
