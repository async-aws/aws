<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\Runtime;

class ListLayerVersionsRequest
{
    /**
     * A runtime identifier. For example, `go1.x`.
     *
     * @var Runtime::NODEJS|Runtime::NODEJS_4_3|Runtime::NODEJS_6_10|Runtime::NODEJS_8_10|Runtime::NODEJS_10_X|Runtime::NODEJS_12_X|Runtime::JAVA_8|Runtime::JAVA_11|Runtime::PYTHON_2_7|Runtime::PYTHON_3_6|Runtime::PYTHON_3_7|Runtime::PYTHON_3_8|Runtime::DOTNETCORE_1_0|Runtime::DOTNETCORE_2_0|Runtime::DOTNETCORE_2_1|Runtime::NODEJS_4_3_EDGE|Runtime::GO_1_X|Runtime::RUBY_2_5|Runtime::PROVIDED|null
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
     *   CompatibleRuntime?: \AsyncAws\Lambda\Enum\Runtime::NODEJS|\AsyncAws\Lambda\Enum\Runtime::NODEJS_4_3|\AsyncAws\Lambda\Enum\Runtime::NODEJS_6_10|\AsyncAws\Lambda\Enum\Runtime::NODEJS_8_10|\AsyncAws\Lambda\Enum\Runtime::NODEJS_10_X|\AsyncAws\Lambda\Enum\Runtime::NODEJS_12_X|\AsyncAws\Lambda\Enum\Runtime::JAVA_8|\AsyncAws\Lambda\Enum\Runtime::JAVA_11|\AsyncAws\Lambda\Enum\Runtime::PYTHON_2_7|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_6|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_7|\AsyncAws\Lambda\Enum\Runtime::PYTHON_3_8|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_1_0|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_2_0|\AsyncAws\Lambda\Enum\Runtime::DOTNETCORE_2_1|\AsyncAws\Lambda\Enum\Runtime::NODEJS_4_3_EDGE|\AsyncAws\Lambda\Enum\Runtime::GO_1_X|\AsyncAws\Lambda\Enum\Runtime::RUBY_2_5|\AsyncAws\Lambda\Enum\Runtime::PROVIDED,
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

    /**
     * @return Runtime::NODEJS|Runtime::NODEJS_4_3|Runtime::NODEJS_6_10|Runtime::NODEJS_8_10|Runtime::NODEJS_10_X|Runtime::NODEJS_12_X|Runtime::JAVA_8|Runtime::JAVA_11|Runtime::PYTHON_2_7|Runtime::PYTHON_3_6|Runtime::PYTHON_3_7|Runtime::PYTHON_3_8|Runtime::DOTNETCORE_1_0|Runtime::DOTNETCORE_2_0|Runtime::DOTNETCORE_2_1|Runtime::NODEJS_4_3_EDGE|Runtime::GO_1_X|Runtime::RUBY_2_5|Runtime::PROVIDED|null
     */
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

    /**
     * @param Runtime::NODEJS|Runtime::NODEJS_4_3|Runtime::NODEJS_6_10|Runtime::NODEJS_8_10|Runtime::NODEJS_10_X|Runtime::NODEJS_12_X|Runtime::JAVA_8|Runtime::JAVA_11|Runtime::PYTHON_2_7|Runtime::PYTHON_3_6|Runtime::PYTHON_3_7|Runtime::PYTHON_3_8|Runtime::DOTNETCORE_1_0|Runtime::DOTNETCORE_2_0|Runtime::DOTNETCORE_2_1|Runtime::NODEJS_4_3_EDGE|Runtime::GO_1_X|Runtime::RUBY_2_5|Runtime::PROVIDED|null $value
     */
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
        if (null !== $this->CompatibleRuntime) {
            if (!isset(Runtime::AVAILABLE_RUNTIME[$this->CompatibleRuntime])) {
                throw new InvalidArgument(sprintf('Invalid parameter "CompatibleRuntime" when validating the "%s". The value "%s" is not a valid "Runtime". Available values are %s.', __CLASS__, $this->CompatibleRuntime, implode(', ', array_keys(Runtime::AVAILABLE_RUNTIME))));
            }
        }

        if (null === $this->LayerName) {
            throw new InvalidArgument(sprintf('Missing parameter "LayerName" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
