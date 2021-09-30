<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\Architecture;
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
     * @var list<Runtime::*>|null
     */
    private $compatibleRuntimes;

    /**
     * The layer's software license. It can be any of the following:.
     *
     * @var string|null
     */
    private $licenseInfo;

    /**
     * A list of compatible instruction set architectures.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/foundation-arch.html
     *
     * @var list<Architecture::*>|null
     */
    private $compatibleArchitectures;

    /**
     * @param array{
     *   LayerName?: string,
     *   Description?: string,
     *   Content?: LayerVersionContentInput|array,
     *   CompatibleRuntimes?: list<Runtime::*>,
     *   LicenseInfo?: string,
     *   CompatibleArchitectures?: list<Architecture::*>,
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
        $this->compatibleArchitectures = $input['CompatibleArchitectures'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Architecture::*>
     */
    public function getCompatibleArchitectures(): array
    {
        return $this->compatibleArchitectures ?? [];
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<Architecture::*> $value
     */
    public function setCompatibleArchitectures(array $value): self
    {
        $this->compatibleArchitectures = $value;

        return $this;
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
        if (null !== $v = $this->compatibleArchitectures) {
            $index = -1;
            $payload['CompatibleArchitectures'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!Architecture::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "CompatibleArchitectures" for "%s". The value "%s" is not a valid "Architecture".', __CLASS__, $listValue));
                }
                $payload['CompatibleArchitectures'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
