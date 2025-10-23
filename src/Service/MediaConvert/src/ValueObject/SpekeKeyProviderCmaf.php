<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * If your output group type is CMAF, use these settings when doing DRM encryption with a SPEKE-compliant key provider.
 * If your output group type is HLS, DASH, or Microsoft Smooth, use the SpekeKeyProvider settings instead.
 */
final class SpekeKeyProviderCmaf
{
    /**
     * If you want your key provider to encrypt the content keys that it provides to MediaConvert, set up a certificate with
     * a master key using AWS Certificate Manager. Specify the certificate's Amazon Resource Name (ARN) here.
     *
     * @var string|null
     */
    private $certificateArn;

    /**
     * Specify the DRM system IDs that you want signaled in the DASH manifest that MediaConvert creates as part of this CMAF
     * package. The DASH manifest can currently signal up to three system IDs. For more information, see
     * https://dashif.org/identifiers/content_protection/.
     *
     * @var string[]|null
     */
    private $dashSignaledSystemIds;

    /**
     * Specify the SPEKE version, either v1.0 or v2.0, that MediaConvert uses when encrypting your output. For more
     * information, see: https://docs.aws.amazon.com/speke/latest/documentation/speke-api-specification.html To use SPEKE
     * v1.0: Leave blank. To use SPEKE v2.0: Specify a SPEKE v2.0 video preset and a SPEKE v2.0 audio preset.
     *
     * @var EncryptionContractConfiguration|null
     */
    private $encryptionContractConfiguration;

    /**
     * Specify up to 3 DRM system IDs that you want signaled in the HLS manifest that MediaConvert creates as part of this
     * CMAF package. For more information, see https://dashif.org/identifiers/content_protection/.
     *
     * @var string[]|null
     */
    private $hlsSignaledSystemIds;

    /**
     * Specify the resource ID that your SPEKE-compliant key provider uses to identify this content.
     *
     * @var string|null
     */
    private $resourceId;

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
     *   DashSignaledSystemIds?: string[]|null,
     *   EncryptionContractConfiguration?: EncryptionContractConfiguration|array|null,
     *   HlsSignaledSystemIds?: string[]|null,
     *   ResourceId?: string|null,
     *   Url?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->certificateArn = $input['CertificateArn'] ?? null;
        $this->dashSignaledSystemIds = $input['DashSignaledSystemIds'] ?? null;
        $this->encryptionContractConfiguration = isset($input['EncryptionContractConfiguration']) ? EncryptionContractConfiguration::create($input['EncryptionContractConfiguration']) : null;
        $this->hlsSignaledSystemIds = $input['HlsSignaledSystemIds'] ?? null;
        $this->resourceId = $input['ResourceId'] ?? null;
        $this->url = $input['Url'] ?? null;
    }

    /**
     * @param array{
     *   CertificateArn?: string|null,
     *   DashSignaledSystemIds?: string[]|null,
     *   EncryptionContractConfiguration?: EncryptionContractConfiguration|array|null,
     *   HlsSignaledSystemIds?: string[]|null,
     *   ResourceId?: string|null,
     *   Url?: string|null,
     * }|SpekeKeyProviderCmaf $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCertificateArn(): ?string
    {
        return $this->certificateArn;
    }

    /**
     * @return string[]
     */
    public function getDashSignaledSystemIds(): array
    {
        return $this->dashSignaledSystemIds ?? [];
    }

    public function getEncryptionContractConfiguration(): ?EncryptionContractConfiguration
    {
        return $this->encryptionContractConfiguration;
    }

    /**
     * @return string[]
     */
    public function getHlsSignaledSystemIds(): array
    {
        return $this->hlsSignaledSystemIds ?? [];
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
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
        if (null !== $v = $this->dashSignaledSystemIds) {
            $index = -1;
            $payload['dashSignaledSystemIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['dashSignaledSystemIds'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->encryptionContractConfiguration) {
            $payload['encryptionContractConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->hlsSignaledSystemIds) {
            $index = -1;
            $payload['hlsSignaledSystemIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['hlsSignaledSystemIds'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->resourceId) {
            $payload['resourceId'] = $v;
        }
        if (null !== $v = $this->url) {
            $payload['url'] = $v;
        }

        return $payload;
    }
}
