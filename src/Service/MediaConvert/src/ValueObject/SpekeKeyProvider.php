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
     * Specify the resource ID that your SPEKE-compliant key provider uses to identify this content.
     *
     * @var string|null
     */
    private $resourceId;

    /**
     * Relates to SPEKE implementation. DRM system identifiers. DASH output groups support a max of two system ids. Other
     * group types support one system id. See
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
     *   CertificateArn?: null|string,
     *   ResourceId?: null|string,
     *   SystemIds?: null|string[],
     *   Url?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->certificateArn = $input['CertificateArn'] ?? null;
        $this->resourceId = $input['ResourceId'] ?? null;
        $this->systemIds = $input['SystemIds'] ?? null;
        $this->url = $input['Url'] ?? null;
    }

    /**
     * @param array{
     *   CertificateArn?: null|string,
     *   ResourceId?: null|string,
     *   SystemIds?: null|string[],
     *   Url?: null|string,
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
