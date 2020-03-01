<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class ListLayerVersionsRequest
{
    /**
     * A runtime identifier. For example, `go1.x`.
     *
     * @var string|null
     */
    private $CompatibleRuntime;

    /**
     * The name or Amazon Resource Name (ARN) of the layer.
     *
     * @required
     *
     * @var string|null
     */
    private $LayerName;

    /**
     * A pagination token returned by a previous call.
     *
     * @var string|null
     */
    private $Marker;

    /**
     * The maximum number of versions to return.
     *
     * @var int|null
     */
    private $MaxItems;

    /**
     * @param array{
     *   CompatibleRuntime?: string,
     *   LayerName?: string,
     *   Marker?: string,
     *   MaxItems?: int,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->CompatibleRuntime = $input['CompatibleRuntime'] ?? null;
        $this->LayerName = $input['LayerName'] ?? null;
        $this->Marker = $input['Marker'] ?? null;
        $this->MaxItems = $input['MaxItems'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCompatibleRuntime(): ?string
    {
        return $this->CompatibleRuntime;
    }

    public function getLayerName(): ?string
    {
        return $this->LayerName;
    }

    public function getMarker(): ?string
    {
        return $this->Marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->MaxItems;
    }

    public function requestBody(): string
    {
        return '{}';
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/json'];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->CompatibleRuntime) {
            $query['CompatibleRuntime'] = $this->CompatibleRuntime;
        }
        if (null !== $this->Marker) {
            $query['Marker'] = $this->Marker;
        }
        if (null !== $this->MaxItems) {
            $query['MaxItems'] = $this->MaxItems;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['LayerName'] = $this->LayerName ?? '';

        return "/2018-10-31/layers/{$uri['LayerName']}/versions";
    }

    public function setCompatibleRuntime(?string $value): self
    {
        $this->CompatibleRuntime = $value;

        return $this;
    }

    public function setLayerName(?string $value): self
    {
        $this->LayerName = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->Marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->MaxItems = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['LayerName'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
