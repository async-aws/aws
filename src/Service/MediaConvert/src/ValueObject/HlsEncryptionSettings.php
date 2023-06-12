<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\HlsEncryptionType;
use AsyncAws\MediaConvert\Enum\HlsInitializationVectorInManifest;
use AsyncAws\MediaConvert\Enum\HlsKeyProviderType;
use AsyncAws\MediaConvert\Enum\HlsOfflineEncrypted;

/**
 * Settings for HLS encryption.
 */
final class HlsEncryptionSettings
{
    /**
     * This is a 128-bit, 16-byte hex value represented by a 32-character text string. If this parameter is not set then the
     * Initialization Vector will follow the segment number by default.
     */
    private $constantInitializationVector;

    /**
     * Encrypts the segments with the given encryption scheme. Leave blank to disable. Selecting 'Disabled' in the web
     * interface also disables encryption.
     */
    private $encryptionMethod;

    /**
     * The Initialization Vector is a 128-bit number used in conjunction with the key for encrypting blocks. If set to
     * INCLUDE, Initialization Vector is listed in the manifest. Otherwise Initialization Vector is not in the manifest.
     */
    private $initializationVectorInManifest;

    /**
     * Enable this setting to insert the EXT-X-SESSION-KEY element into the master playlist. This allows for offline Apple
     * HLS FairPlay content protection.
     */
    private $offlineEncrypted;

    /**
     * If your output group type is HLS, DASH, or Microsoft Smooth, use these settings when doing DRM encryption with a
     * SPEKE-compliant key provider. If your output group type is CMAF, use the SpekeKeyProviderCmaf settings instead.
     */
    private $spekeKeyProvider;

    /**
     * Use these settings to set up encryption with a static key provider.
     */
    private $staticKeyProvider;

    /**
     * Specify whether your DRM encryption key is static or from a key provider that follows the SPEKE standard. For more
     * information about SPEKE, see https://docs.aws.amazon.com/speke/latest/documentation/what-is-speke.html.
     */
    private $type;

    /**
     * @param array{
     *   ConstantInitializationVector?: null|string,
     *   EncryptionMethod?: null|HlsEncryptionType::*,
     *   InitializationVectorInManifest?: null|HlsInitializationVectorInManifest::*,
     *   OfflineEncrypted?: null|HlsOfflineEncrypted::*,
     *   SpekeKeyProvider?: null|SpekeKeyProvider|array,
     *   StaticKeyProvider?: null|StaticKeyProvider|array,
     *   Type?: null|HlsKeyProviderType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->constantInitializationVector = $input['ConstantInitializationVector'] ?? null;
        $this->encryptionMethod = $input['EncryptionMethod'] ?? null;
        $this->initializationVectorInManifest = $input['InitializationVectorInManifest'] ?? null;
        $this->offlineEncrypted = $input['OfflineEncrypted'] ?? null;
        $this->spekeKeyProvider = isset($input['SpekeKeyProvider']) ? SpekeKeyProvider::create($input['SpekeKeyProvider']) : null;
        $this->staticKeyProvider = isset($input['StaticKeyProvider']) ? StaticKeyProvider::create($input['StaticKeyProvider']) : null;
        $this->type = $input['Type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConstantInitializationVector(): ?string
    {
        return $this->constantInitializationVector;
    }

    /**
     * @return HlsEncryptionType::*|null
     */
    public function getEncryptionMethod(): ?string
    {
        return $this->encryptionMethod;
    }

    /**
     * @return HlsInitializationVectorInManifest::*|null
     */
    public function getInitializationVectorInManifest(): ?string
    {
        return $this->initializationVectorInManifest;
    }

    /**
     * @return HlsOfflineEncrypted::*|null
     */
    public function getOfflineEncrypted(): ?string
    {
        return $this->offlineEncrypted;
    }

    public function getSpekeKeyProvider(): ?SpekeKeyProvider
    {
        return $this->spekeKeyProvider;
    }

    public function getStaticKeyProvider(): ?StaticKeyProvider
    {
        return $this->staticKeyProvider;
    }

    /**
     * @return HlsKeyProviderType::*|null
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
            if (!HlsEncryptionType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "encryptionMethod" for "%s". The value "%s" is not a valid "HlsEncryptionType".', __CLASS__, $v));
            }
            $payload['encryptionMethod'] = $v;
        }
        if (null !== $v = $this->initializationVectorInManifest) {
            if (!HlsInitializationVectorInManifest::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "initializationVectorInManifest" for "%s". The value "%s" is not a valid "HlsInitializationVectorInManifest".', __CLASS__, $v));
            }
            $payload['initializationVectorInManifest'] = $v;
        }
        if (null !== $v = $this->offlineEncrypted) {
            if (!HlsOfflineEncrypted::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "offlineEncrypted" for "%s". The value "%s" is not a valid "HlsOfflineEncrypted".', __CLASS__, $v));
            }
            $payload['offlineEncrypted'] = $v;
        }
        if (null !== $v = $this->spekeKeyProvider) {
            $payload['spekeKeyProvider'] = $v->requestBody();
        }
        if (null !== $v = $this->staticKeyProvider) {
            $payload['staticKeyProvider'] = $v->requestBody();
        }
        if (null !== $v = $this->type) {
            if (!HlsKeyProviderType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "HlsKeyProviderType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
