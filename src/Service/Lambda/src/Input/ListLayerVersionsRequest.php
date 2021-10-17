<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\Runtime;

final class ListLayerVersionsRequest extends Input
{
    /**
     * A runtime identifier. For example, `go1.x`.
     *
     * @var Runtime::*|null
     */
    private $compatibleRuntime;

    /**
     * The name or Amazon Resource Name (ARN) of the layer.
     *
     * @required
     *
     * @var string|null
     */
    private $layerName;

    /**
     * A pagination token returned by a previous call.
     *
     * @var string|null
     */
    private $marker;

    /**
     * The maximum number of versions to return.
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * The compatible instruction set architecture.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/foundation-arch.html
     *
     * @var Architecture::*|null
     */
    private $compatibleArchitecture;

    /**
     * @param array{
     *   CompatibleRuntime?: Runtime::*,
     *   LayerName?: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   CompatibleArchitecture?: Architecture::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->compatibleRuntime = $input['CompatibleRuntime'] ?? null;
        $this->layerName = $input['LayerName'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        $this->compatibleArchitecture = $input['CompatibleArchitecture'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Architecture::*|null
     */
    public function getCompatibleArchitecture(): ?string
    {
        return $this->compatibleArchitecture;
    }

    /**
     * @return Runtime::*|null
     */
    public function getCompatibleRuntime(): ?string
    {
        return $this->compatibleRuntime;
    }

    public function getLayerName(): ?string
    {
        return $this->layerName;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
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
        if (null !== $this->compatibleRuntime) {
            if (!Runtime::exists($this->compatibleRuntime)) {
                throw new InvalidArgument(sprintf('Invalid parameter "CompatibleRuntime" for "%s". The value "%s" is not a valid "Runtime".', __CLASS__, $this->compatibleRuntime));
            }
            $query['CompatibleRuntime'] = $this->compatibleRuntime;
        }
        if (null !== $this->marker) {
            $query['Marker'] = $this->marker;
        }
        if (null !== $this->maxItems) {
            $query['MaxItems'] = (string) $this->maxItems;
        }
        if (null !== $this->compatibleArchitecture) {
            if (!Architecture::exists($this->compatibleArchitecture)) {
                throw new InvalidArgument(sprintf('Invalid parameter "CompatibleArchitecture" for "%s". The value "%s" is not a valid "Architecture".', __CLASS__, $this->compatibleArchitecture));
            }
            $query['CompatibleArchitecture'] = $this->compatibleArchitecture;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->layerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['LayerName'] = $v;
        $uriString = '/2018-10-31/layers/' . rawurlencode($uri['LayerName']) . '/versions';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param Architecture::*|null $value
     */
    public function setCompatibleArchitecture(?string $value): self
    {
        $this->compatibleArchitecture = $value;

        return $this;
    }

    /**
     * @param Runtime::*|null $value
     */
    public function setCompatibleRuntime(?string $value): self
    {
        $this->compatibleRuntime = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->layerName = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
    }
}
