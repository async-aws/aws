<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;

final class PublishLayerVersionRequest extends Input
{
    /**
     * The name or Amazon Resource Name (ARN) of the layer.
     *
     * @required
     *
     * @var string|null
     */
    private $layerName;

    /**
     * The description of the version.
     *
     * @var string|null
     */
    private $description;

    /**
     * The function layer archive.
     *
     * @required
     *
     * @var LayerVersionContentInput|null
     */
    private $content;

    /**
     * A list of compatible function runtimes. Used for filtering with ListLayers and ListLayerVersions.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     *
     * @var null|list<Runtime::*>
     */
    private $compatibleRuntimes;

    /**
     * The layer's software license. It can be any of the following:.
     *
     * @var string|null
     */
    private $licenseInfo;

    /**
     * @param array{
     *   LayerName?: string,
     *   Description?: string,
     *   Content?: LayerVersionContentInput|array,
     *   CompatibleRuntimes?: list<Runtime::*>,
     *   LicenseInfo?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->layerName = $input['LayerName'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->content = isset($input['Content']) ? LayerVersionContentInput::create($input['Content']) : null;
        $this->compatibleRuntimes = $input['CompatibleRuntimes'] ?? null;
        $this->licenseInfo = $input['LicenseInfo'] ?? null;
        parent::__construct($input);
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
        return $this->compatibleRuntimes ?? [];
    }

    public function getContent(): ?LayerVersionContentInput
    {
        return $this->content;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLayerName(): ?string
    {
        return $this->layerName;
    }

    public function getLicenseInfo(): ?string
    {
        return $this->licenseInfo;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->layerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['LayerName'] = $v;
        $uriString = '/2018-10-31/layers/' . rawurlencode($uri['LayerName']) . '/versions';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<Runtime::*> $value
     */
    public function setCompatibleRuntimes(array $value): self
    {
        $this->compatibleRuntimes = $value;

        return $this;
    }

    public function setContent(?LayerVersionContentInput $value): self
    {
        $this->content = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->layerName = $value;

        return $this;
    }

    public function setLicenseInfo(?string $value): self
    {
        $this->licenseInfo = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null === $v = $this->content) {
            throw new InvalidArgument(sprintf('Missing parameter "Content" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Content'] = $v->requestBody();
        if (null !== $v = $this->compatibleRuntimes) {
            $index = -1;
            $payload['CompatibleRuntimes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!Runtime::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "CompatibleRuntimes" for "%s". The value "%s" is not a valid "Runtime".', __CLASS__, $listValue));
                }
                $payload['CompatibleRuntimes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->licenseInfo) {
            $payload['LicenseInfo'] = $v;
        }

        return $payload;
    }
}
