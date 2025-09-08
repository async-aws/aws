<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CmafEncryptionType;
use AsyncAws\MediaConvert\Enum\CmafInitializationVectorInManifest;
use AsyncAws\MediaConvert\Enum\CmafKeyProviderType;

/**
 * Settings for CMAF encryption.
 */
final class CmafEncryptionSettings
{
    /**
     * This is a 128-bit, 16-byte hex value represented by a 32-character text string. If this parameter is not set then the
     * Initialization Vector will follow the segment number by default.
     *
     * @var string|null
     */
    private $constantInitializationVector;

    /**
     * Specify the encryption scheme that you want the service to use when encrypting your CMAF segments. Choose AES-CBC
     * subsample or AES_CTR.
     *
     * @var CmafEncryptionType::*|null
     */
    private $encryptionMethod;

    /**
     * When you use DRM with CMAF outputs, choose whether the service writes the 128-bit encryption initialization vector in
     * the HLS and DASH manifests.
     *
     * @var CmafInitializationVectorInManifest::*|null
     */
    private $initializationVectorInManifest;

    /**
     * If your output group type is CMAF, use these settings when doing DRM encryption with a SPEKE-compliant key provider.
     * If your output group type is HLS, DASH, or Microsoft Smooth, use the SpekeKeyProvider settings instead.
     *
     * @var SpekeKeyProviderCmaf|null
     */
    private $spekeKeyProvider;

    /**
     * Use these settings to set up encryption with a static key provider.
     *
     * @var StaticKeyProvider|null
     */
    private $staticKeyProvider;

    /**
     * Specify whether your DRM encryption key is static or from a key provider that follows the SPEKE standard. For more
     * information about SPEKE, see https://docs.aws.amazon.com/speke/latest/documentation/what-is-speke.html.
     *
     * @var CmafKeyProviderType::*|null
     */
    private $type;

    /**
     * @param array{
     *   ConstantInitializationVector?: string|null,
     *   EncryptionMethod?: CmafEncryptionType::*|null,
     *   InitializationVectorInManifest?: CmafInitializationVectorInManifest::*|null,
     *   SpekeKeyProvider?: SpekeKeyProviderCmaf|array|null,
     *   StaticKeyProvider?: StaticKeyProvider|array|null,
     *   Type?: CmafKeyProviderType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->constantInitializationVector = $input['ConstantInitializationVector'] ?? null;
        $this->encryptionMethod = $input['EncryptionMethod'] ?? null;
        $this->initializationVectorInManifest = $input['InitializationVectorInManifest'] ?? null;
        $this->spekeKeyProvider = isset($input['SpekeKeyProvider']) ? SpekeKeyProviderCmaf::create($input['SpekeKeyProvider']) : null;
        $this->staticKeyProvider = isset($input['StaticKeyProvider']) ? StaticKeyProvider::create($input['StaticKeyProvider']) : null;
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   ConstantInitializationVector?: string|null,
     *   EncryptionMethod?: CmafEncryptionType::*|null,
     *   InitializationVectorInManifest?: CmafInitializationVectorInManifest::*|null,
     *   SpekeKeyProvider?: SpekeKeyProviderCmaf|array|null,
     *   StaticKeyProvider?: StaticKeyProvider|array|null,
     *   Type?: CmafKeyProviderType::*|null,
     * }|CmafEncryptionSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConstantInitializationVector(): ?string
    {
        return $this->constantInitializationVector;
    }

    /**
     * @return CmafEncryptionType::*|null
     */
    public function getEncryptionMethod(): ?string
    {
        return $this->encryptionMethod;
    }

    /**
     * @return CmafInitializationVectorInManifest::*|null
     */
    public function getInitializationVectorInManifest(): ?string
    {
        return $this->initializationVectorInManifest;
    }

    public function getSpekeKeyProvider(): ?SpekeKeyProviderCmaf
    {
        return $this->spekeKeyProvider;
    }

    public function getStaticKeyProvider(): ?StaticKeyProvider
    {
        return $this->staticKeyProvider;
    }

    /**
     * @return CmafKeyProviderType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->constantInitializationVector) {
            $payload['constantInitializationVector'] = $v;
        }
        if (null !== $v = $this->encryptionMethod) {
            if (!CmafEncryptionType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "encryptionMethod" for "%s". The value "%s" is not a valid "CmafEncryptionType".', __CLASS__, $v));
            }
            $payload['encryptionMethod'] = $v;
        }
        if (null !== $v = $this->initializationVectorInManifest) {
            if (!CmafInitializationVectorInManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "initializationVectorInManifest" for "%s". The value "%s" is not a valid "CmafInitializationVectorInManifest".', __CLASS__, $v));
            }
            $payload['initializationVectorInManifest'] = $v;
        }
        if (null !== $v = $this->spekeKeyProvider) {
            $payload['spekeKeyProvider'] = $v->requestBody();
        }
        if (null !== $v = $this->staticKeyProvider) {
            $payload['staticKeyProvider'] = $v->requestBody();
        }
        if (null !== $v = $this->type) {
            if (!CmafKeyProviderType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "CmafKeyProviderType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
