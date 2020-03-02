<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\Runtime;

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
     * @var list<Runtime::*>
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
     *   CompatibleRuntimes?: list<\AsyncAws\Lambda\Enum\Runtime::*>,
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

    /**
     * @return list<Runtime::*>
     */
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

    public function requestBody(): string
    {
        $payload = [];
        $indices = new \stdClass();
        if (null !== $v = $this->Description) {
            $payload['Description'] = $v;
        }

        if (null !== $this->Content) {
            (static function (LayerVersionContentInput $input) use (&$payload) {
                if (null !== $v = $input->getS3Bucket()) {
                    $payload['Content']['S3Bucket'] = $v;
                }

                if (null !== $v = $input->getS3Key()) {
                    $payload['Content']['S3Key'] = $v;
                }

                if (null !== $v = $input->getS3ObjectVersion()) {
                    $payload['Content']['S3ObjectVersion'] = $v;
                }

                if (null !== $v = $input->getZipFile()) {
                    $payload['Content']['ZipFile'] = base64_encode($v);
                }
            })($this->Content);
        }

        (static function (array $input) use (&$payload, $indices) {
            $indices->kea6f923 = -1;
            foreach ($input as $value) {
                ++$indices->kea6f923;
                $payload['CompatibleRuntimes'][$indices->kea6f923] = $value;
            }
        })($this->CompatibleRuntimes);
        if (null !== $v = $this->LicenseInfo) {
            $payload['LicenseInfo'] = $v;
        }

        return json_encode($payload);
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/json'];

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

    /**
     * @param list<Runtime::*> $value
     */
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
        if (null === $this->LayerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Content) {
            throw new InvalidArgument(sprintf('Missing parameter "Content" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        $this->Content->validate();

        foreach ($this->CompatibleRuntimes as $item) {
            if (!Runtime::exists($item)) {
                throw new InvalidArgument(sprintf('Invalid parameter "CompatibleRuntimes" when validating the "%s". The value "%s" is not a valid "Runtime".', __CLASS__, $item));
            }
        }
    }
}
