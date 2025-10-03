<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * If your output group type is HLS, DASH, or Microsoft Smooth, use these settings when doing DRM encryption with a
 * SPEKE-compliant key provider. If your output group type is CMAF, use the SpekeKeyProviderCmaf settings instead.
 */
final class SpekeKeyProvider
{
    /**
     * If you want your key provider to encrypt the content keys that it provides to MediaConvert, set up a certificate with
     * a master key using AWS Certificate Manager. Specify the certificate's Amazon Resource Name (ARN) here.
     *
     * @var string|null
     */
    private $certificateArn;

    /**
     * Specify the SPEKE version, either v1.0 or v2.0, that MediaConvert uses when encrypting your output. For more
     * information, see: https://docs.aws.amazon.com/speke/latest/documentation/speke-api-specification.html To use SPEKE
     * v1.0: Leave blank. To use SPEKE v2.0: Specify a SPEKE v2.0 video preset and a SPEKE v2.0 audio preset.
     *
     * @var EncryptionContractConfiguration|null
     */
    private $encryptionContractConfiguration;

    /**
     * Specify the resource ID that your SPEKE-compliant key provider uses to identify this content.
     *
     * @var string|null
     */
    private $resourceId;

    /**
     * Relates to SPEKE implementation. DRM system identifiers. DASH output groups support a max of two system ids. HLS
     * output groups support a max of 3 system ids. Other group types support one system id. See
     * https://dashif.org/identifiers/content_protection/ for more details.
     *
     * @var string[]|null
     */
    private $systemIds;

    /**
     * Specify the URL to the key server that your SPEKE-compliant DRM key provider uses to provide keys for encrypting your
     * content.
     *
     * @var string|null
     */
    private $url;

    /**
     * @param array{
     *   CertificateArn?: string|null,
     *   EncryptionContractConfiguration?: EncryptionContractConfiguration|array|null,
     *   ResourceId?: string|null,
     *   SystemIds?: string[]|null,
     *   Url?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->certificateArn = $input['CertificateArn'] ?? null;
        $this->encryptionContractConfiguration = isset($input['EncryptionContractConfiguration']) ? EncryptionContractConfiguration::create($input['EncryptionContractConfiguration']) : null;
        $this->resourceId = $input['ResourceId'] ?? null;
        $this->systemIds = $input['SystemIds'] ?? null;
        $this->url = $input['Url'] ?? null;
    }

    /**
     * @param array{
     *   CertificateArn?: string|null,
     *   EncryptionContractConfiguration?: EncryptionContractConfiguration|array|null,
     *   ResourceId?: string|null,
     *   SystemIds?: string[]|null,
     *   Url?: string|null,
     * }|SpekeKeyProvider $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCertificateArn(): ?string
    {
        return $this->certificateArn;
    }

    public function getEncryptionContractConfiguration(): ?EncryptionContractConfiguration
    {
        return $this->encryptionContractConfiguration;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    /**
     * @return string[]
     */
    public function getSystemIds(): array
    {
        return $this->systemIds ?? [];
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->certificateArn) {
            $payload['certificateArn'] = $v;
        }
        if (null !== $v = $this->encryptionContractConfiguration) {
            $payload['encryptionContractConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->resourceId) {
            $payload['resourceId'] = $v;
        }
        if (null !== $v = $this->systemIds) {
            $index = -1;
            $payload['systemIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['systemIds'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->url) {
            $payload['url'] = $v;
        }

        return $payload;
    }
}
