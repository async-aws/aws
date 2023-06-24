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
     */
    private $certificateArn;

    /**
     * Specify the DRM system IDs that you want signaled in the DASH manifest that MediaConvert creates as part of this CMAF
     * package. The DASH manifest can currently signal up to three system IDs. For more information, see
     * https://dashif.org/identifiers/content_protection/.
     */
    private $dashSignaledSystemIds;

    /**
     * Specify the DRM system ID that you want signaled in the HLS manifest that MediaConvert creates as part of this CMAF
     * package. The HLS manifest can currently signal only one system ID. For more information, see
     * https://dashif.org/identifiers/content_protection/.
     */
    private $hlsSignaledSystemIds;

    /**
     * Specify the resource ID that your SPEKE-compliant key provider uses to identify this content.
     */
    private $resourceId;

    /**
     * Specify the URL to the key server that your SPEKE-compliant DRM key provider uses to provide keys for encrypting your
     * content.
     */
    private $url;

    /**
     * @param array{
     *   CertificateArn?: null|string,
     *   DashSignaledSystemIds?: null|string[],
     *   HlsSignaledSystemIds?: null|string[],
     *   ResourceId?: null|string,
     *   Url?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->certificateArn = $input['CertificateArn'] ?? null;
        $this->dashSignaledSystemIds = $input['DashSignaledSystemIds'] ?? null;
        $this->hlsSignaledSystemIds = $input['HlsSignaledSystemIds'] ?? null;
        $this->resourceId = $input['ResourceId'] ?? null;
        $this->url = $input['Url'] ?? null;
    }

    /**
     * @param array{
     *   CertificateArn?: null|string,
     *   DashSignaledSystemIds?: null|string[],
     *   HlsSignaledSystemIds?: null|string[],
     *   ResourceId?: null|string,
     *   Url?: null|string,
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
