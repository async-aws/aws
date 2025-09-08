<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * ESAM ManifestConfirmConditionNotification defined by OC-SP-ESAM-API-I03-131025.
 */
final class EsamManifestConfirmConditionNotification
{
    /**
     * Provide your ESAM ManifestConfirmConditionNotification XML document inside your JSON job settings. Form the XML
     * document as per OC-SP-ESAM-API-I03-131025. The transcoder will use the Manifest Conditioning instructions in the
     * message that you supply.
     *
     * @var string|null
     */
    private $mccXml;

    /**
     * @param array{
     *   MccXml?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mccXml = $input['MccXml'] ?? null;
    }

    /**
     * @param array{
     *   MccXml?: string|null,
     * }|EsamManifestConfirmConditionNotification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMccXml(): ?string
    {
        return $this->mccXml;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->mccXml) {
            $payload['mccXml'] = $v;
        }

        return $payload;
    }
}
