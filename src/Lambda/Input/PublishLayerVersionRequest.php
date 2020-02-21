<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class PublishLayerVersionRequest
{
    /**
     * The name or Amazon Resource Name (ARN) of the layer.
     *
     * @required
     *
     * @var string|null
     */
    private $LayerName;

    /**
     * The description of the version.
     *
     * @var string|null
     */
    private $Description;

    /**
     * The function layer archive.
     *
     * @required
     *
     * @var LayerVersionContentInput|null
     */
    private $Content;

    /**
     * A list of compatible function runtimes. Used for filtering with ListLayers and ListLayerVersions.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     *
     * @var string[]
     */
    private $CompatibleRuntimes;

    /**
     * The layer's software license. It can be any of the following:.
     *
     * @var string|null
     */
    private $LicenseInfo;

    /**
     * @param array{
     *   LayerName?: string,
     *   Description?: string,
     *   Content?: \AsyncAws\Lambda\Input\LayerVersionContentInput|array,
     *   CompatibleRuntimes?: string[],
     *   LicenseInfo?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->LayerName = $input['LayerName'] ?? null;
        $this->Description = $input['Description'] ?? null;
        $this->Content = isset($input['Content']) ? LayerVersionContentInput::create($input['Content']) : null;
        $this->CompatibleRuntimes = $input['CompatibleRuntimes'] ?? [];
        $this->LicenseInfo = $input['LicenseInfo'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCompatibleRuntimes(): array
    {
        return $this->CompatibleRuntimes;
    }

    public function getContent(): ?LayerVersionContentInput
    {
        return $this->Content;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function getLayerName(): ?string
    {
        return $this->LayerName;
    }

    public function getLicenseInfo(): ?string
    {
        return $this->LicenseInfo;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'PublishLayerVersion', 'Version' => '2015-03-31'];
        $indices = new \stdClass();

        if (null !== $v = $this->Description) {
            $payload['Description'] = $v;
        }

        (static function ($input) use (&$payload) {
            if (null !== $v = $input->getS3Bucket()) {
                $payload['Content.S3Bucket'] = $v;
            }
            if (null !== $v = $input->getS3Key()) {
                $payload['Content.S3Key'] = $v;
            }
            if (null !== $v = $input->getS3ObjectVersion()) {
                $payload['Content.S3ObjectVersion'] = $v;
            }
            if (null !== $v = $input->getZipFile()) {
                $payload['Content.ZipFile'] = base64_encode($v);
            }
        })($this->Content);
        (static function ($input) use (&$payload, $indices) {
            $indices->k45e4982 = 0;
            foreach ($input as $value) {
                ++$indices->k45e4982;
                $payload["CompatibleRuntimes.{$indices->k45e4982}"] = $value;
            }
        })($this->CompatibleRuntimes);

        if (null !== $v = $this->LicenseInfo) {
            $payload['LicenseInfo'] = $v;
        }

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['LayerName'] = $this->LayerName ?? '';

        return "/2018-10-31/layers/{$uri['LayerName']}/versions";
    }

    public function setCompatibleRuntimes(array $value): self
    {
        $this->CompatibleRuntimes = $value;

        return $this;
    }

    public function setContent(?LayerVersionContentInput $value): self
    {
        $this->Content = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->Description = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->LayerName = $value;

        return $this;
    }

    public function setLicenseInfo(?string $value): self
    {
        $this->LicenseInfo = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['LayerName', 'Content'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        if ($this->Content) {
            $this->Content->validate();
        }
    }
}
