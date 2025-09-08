<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings only when you use Kantar watermarking. Specify the values that MediaConvert uses to generate and
 * place Kantar watermarks in your output audio. These settings apply to every output in your job. In addition to
 * specifying these values, you also need to store your Kantar credentials in AWS Secrets Manager. For more information,
 * see https://docs.aws.amazon.com/mediaconvert/latest/ug/kantar-watermarking.html.
 */
final class KantarWatermarkSettings
{
    /**
     * Provide an audio channel name from your Kantar audio license.
     *
     * @var string|null
     */
    private $channelName;

    /**
     * Specify a unique identifier for Kantar to use for this piece of content.
     *
     * @var string|null
     */
    private $contentReference;

    /**
     * Provide the name of the AWS Secrets Manager secret where your Kantar credentials are stored. Note that your
     * MediaConvert service role must provide access to this secret. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/granting-permissions-for-mediaconvert-to-access-secrets-manager-secret.html.
     * For instructions on creating a secret, see
     * https://docs.aws.amazon.com/secretsmanager/latest/userguide/tutorials_basic.html, in the AWS Secrets Manager User
     * Guide.
     *
     * @var string|null
     */
    private $credentialsSecretName;

    /**
     * Optional. Specify an offset, in whole seconds, from the start of your output and the beginning of the watermarking.
     * When you don't specify an offset, Kantar defaults to zero.
     *
     * @var float|null
     */
    private $fileOffset;

    /**
     * Provide your Kantar license ID number. You should get this number from Kantar.
     *
     * @var int|null
     */
    private $kantarLicenseId;

    /**
     * Provide the HTTPS endpoint to the Kantar server. You should get this endpoint from Kantar.
     *
     * @var string|null
     */
    private $kantarServerUrl;

    /**
     * Optional. Specify the Amazon S3 bucket where you want MediaConvert to store your Kantar watermark XML logs. When you
     * don't specify a bucket, MediaConvert doesn't save these logs. Note that your MediaConvert service role must provide
     * access to this location. For more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/iam-role.html.
     *
     * @var string|null
     */
    private $logDestination;

    /**
     * You can optionally use this field to specify the first timestamp that Kantar embeds during watermarking. Kantar
     * suggests that you be very cautious when using this Kantar feature, and that you use it only on channels that are
     * managed specifically for use with this feature by your Audience Measurement Operator. For more information about this
     * feature, contact Kantar technical support.
     *
     * @var string|null
     */
    private $metadata3;

    /**
     * Additional metadata that MediaConvert sends to Kantar. Maximum length is 50 characters.
     *
     * @var string|null
     */
    private $metadata4;

    /**
     * Additional metadata that MediaConvert sends to Kantar. Maximum length is 50 characters.
     *
     * @var string|null
     */
    private $metadata5;

    /**
     * Additional metadata that MediaConvert sends to Kantar. Maximum length is 50 characters.
     *
     * @var string|null
     */
    private $metadata6;

    /**
     * Additional metadata that MediaConvert sends to Kantar. Maximum length is 50 characters.
     *
     * @var string|null
     */
    private $metadata7;

    /**
     * Additional metadata that MediaConvert sends to Kantar. Maximum length is 50 characters.
     *
     * @var string|null
     */
    private $metadata8;

    /**
     * @param array{
     *   ChannelName?: string|null,
     *   ContentReference?: string|null,
     *   CredentialsSecretName?: string|null,
     *   FileOffset?: float|null,
     *   KantarLicenseId?: int|null,
     *   KantarServerUrl?: string|null,
     *   LogDestination?: string|null,
     *   Metadata3?: string|null,
     *   Metadata4?: string|null,
     *   Metadata5?: string|null,
     *   Metadata6?: string|null,
     *   Metadata7?: string|null,
     *   Metadata8?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channelName = $input['ChannelName'] ?? null;
        $this->contentReference = $input['ContentReference'] ?? null;
        $this->credentialsSecretName = $input['CredentialsSecretName'] ?? null;
        $this->fileOffset = $input['FileOffset'] ?? null;
        $this->kantarLicenseId = $input['KantarLicenseId'] ?? null;
        $this->kantarServerUrl = $input['KantarServerUrl'] ?? null;
        $this->logDestination = $input['LogDestination'] ?? null;
        $this->metadata3 = $input['Metadata3'] ?? null;
        $this->metadata4 = $input['Metadata4'] ?? null;
        $this->metadata5 = $input['Metadata5'] ?? null;
        $this->metadata6 = $input['Metadata6'] ?? null;
        $this->metadata7 = $input['Metadata7'] ?? null;
        $this->metadata8 = $input['Metadata8'] ?? null;
    }

    /**
     * @param array{
     *   ChannelName?: string|null,
     *   ContentReference?: string|null,
     *   CredentialsSecretName?: string|null,
     *   FileOffset?: float|null,
     *   KantarLicenseId?: int|null,
     *   KantarServerUrl?: string|null,
     *   LogDestination?: string|null,
     *   Metadata3?: string|null,
     *   Metadata4?: string|null,
     *   Metadata5?: string|null,
     *   Metadata6?: string|null,
     *   Metadata7?: string|null,
     *   Metadata8?: string|null,
     * }|KantarWatermarkSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getChannelName(): ?string
    {
        return $this->channelName;
    }

    public function getContentReference(): ?string
    {
        return $this->contentReference;
    }

    public function getCredentialsSecretName(): ?string
    {
        return $this->credentialsSecretName;
    }

    public function getFileOffset(): ?float
    {
        return $this->fileOffset;
    }

    public function getKantarLicenseId(): ?int
    {
        return $this->kantarLicenseId;
    }

    public function getKantarServerUrl(): ?string
    {
        return $this->kantarServerUrl;
    }

    public function getLogDestination(): ?string
    {
        return $this->logDestination;
    }

    public function getMetadata3(): ?string
    {
        return $this->metadata3;
    }

    public function getMetadata4(): ?string
    {
        return $this->metadata4;
    }

    public function getMetadata5(): ?string
    {
        return $this->metadata5;
    }

    public function getMetadata6(): ?string
    {
        return $this->metadata6;
    }

    public function getMetadata7(): ?string
    {
        return $this->metadata7;
    }

    public function getMetadata8(): ?string
    {
        return $this->metadata8;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->channelName) {
            $payload['channelName'] = $v;
        }
        if (null !== $v = $this->contentReference) {
            $payload['contentReference'] = $v;
        }
        if (null !== $v = $this->credentialsSecretName) {
            $payload['credentialsSecretName'] = $v;
        }
        if (null !== $v = $this->fileOffset) {
            $payload['fileOffset'] = $v;
        }
        if (null !== $v = $this->kantarLicenseId) {
            $payload['kantarLicenseId'] = $v;
        }
        if (null !== $v = $this->kantarServerUrl) {
            $payload['kantarServerUrl'] = $v;
        }
        if (null !== $v = $this->logDestination) {
            $payload['logDestination'] = $v;
        }
        if (null !== $v = $this->metadata3) {
            $payload['metadata3'] = $v;
        }
        if (null !== $v = $this->metadata4) {
            $payload['metadata4'] = $v;
        }
        if (null !== $v = $this->metadata5) {
            $payload['metadata5'] = $v;
        }
        if (null !== $v = $this->metadata6) {
            $payload['metadata6'] = $v;
        }
        if (null !== $v = $this->metadata7) {
            $payload['metadata7'] = $v;
        }
        if (null !== $v = $this->metadata8) {
            $payload['metadata8'] = $v;
        }

        return $payload;
    }
}
