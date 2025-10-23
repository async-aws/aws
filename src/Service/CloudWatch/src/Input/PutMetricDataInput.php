<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\ValueObject\Entity;
use AsyncAws\CloudWatch\ValueObject\EntityMetricData;
use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutMetricDataInput extends Input
{
    /**
     * The namespace for the metric data. You can use ASCII characters for the namespace, except for control characters
     * which are not supported.
     *
     * To avoid conflicts with Amazon Web Services service namespaces, you should not specify a namespace that begins with
     * `AWS/`
     *
     * @required
     *
     * @var string|null
     */
    private $namespace;

    /**
     * The data for the metrics. Use this parameter if your metrics do not contain associated entities. The array can
     * include no more than 1000 metrics per call.
     *
     * The limit of metrics allowed, 1000, is the sum of both `EntityMetricData` and `MetricData` metrics.
     *
     * @var MetricDatum[]|null
     */
    private $metricData;

    /**
     * Data for metrics that contain associated entity information. You can include up to two `EntityMetricData` objects,
     * each of which can contain a single `Entity` and associated metrics.
     *
     * The limit of metrics allowed, 1000, is the sum of both `EntityMetricData` and `MetricData` metrics.
     *
     * @var EntityMetricData[]|null
     */
    private $entityMetricData;

    /**
     * Whether to accept valid metric data when an invalid entity is sent.
     *
     * - When set to `true`: Any validation error (for entity or metric data) will fail the entire request, and no data will
     *   be ingested. The failed operation will return a 400 result with the error.
     * - When set to `false`: Validation errors in the entity will not associate the metric with the entity, but the metric
     *   data will still be accepted and ingested. Validation errors in the metric data will fail the entire request, and no
     *   data will be ingested.
     *
     *   In the case of an invalid entity, the operation will return a `200` status, but an additional response header will
     *   contain information about the validation errors. The new header, `X-Amzn-Failure-Message` is an enumeration of the
     *   following values:
     *
     *   - `InvalidEntity` - The provided entity is invalid.
     *   - `InvalidKeyAttributes` - The provided `KeyAttributes` of an entity is invalid.
     *   - `InvalidAttributes` - The provided `Attributes` of an entity is invalid.
     *   - `InvalidTypeValue` - The provided `Type` in the `KeyAttributes` of an entity is invalid.
     *   - `EntitySizeTooLarge` - The number of `EntityMetricData` objects allowed is 2.
     *   - `MissingRequiredFields` - There are missing required fields in the `KeyAttributes` for the provided `Type`.
     *
     *   For details of the requirements for specifying an entity, see How to add related information to telemetry [^1] in
     *   the *CloudWatch User Guide*.
     *
     * This parameter is *required* when `EntityMetricData` is included.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/adding-your-own-related-telemetry.html
     *
     * @var bool|null
     */
    private $strictEntityValidation;

    /**
     * @param array{
     *   Namespace?: string,
     *   MetricData?: array<MetricDatum|array>|null,
     *   EntityMetricData?: array<EntityMetricData|array>|null,
     *   StrictEntityValidation?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricData = isset($input['MetricData']) ? array_map([MetricDatum::class, 'create'], $input['MetricData']) : null;
        $this->entityMetricData = isset($input['EntityMetricData']) ? array_map([EntityMetricData::class, 'create'], $input['EntityMetricData']) : null;
        $this->strictEntityValidation = $input['StrictEntityValidation'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Namespace?: string,
     *   MetricData?: array<MetricDatum|array>|null,
     *   EntityMetricData?: array<EntityMetricData|array>|null,
     *   StrictEntityValidation?: bool|null,
     *   '@region'?: string|null,
     * }|PutMetricDataInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EntityMetricData[]
     */
    public function getEntityMetricData(): array
    {
        return $this->entityMetricData ?? [];
    }

    /**
     * @return MetricDatum[]
     */
    public function getMetricData(): array
    {
        return $this->metricData ?? [];
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getStrictEntityValidation(): ?bool
    {
        return $this->strictEntityValidation;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'PutMetricData', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param EntityMetricData[] $value
     */
    public function setEntityMetricData(array $value): self
    {
        $this->entityMetricData = $value;

        return $this;
    }

    /**
     * @param MetricDatum[] $value
     */
    public function setMetricData(array $value): self
    {
        $this->metricData = $value;

        return $this;
    }

    public function setNamespace(?string $value): self
    {
        $this->namespace = $value;

        return $this;
    }

    public function setStrictEntityValidation(?bool $value): self
    {
        $this->strictEntityValidation = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->namespace) {
            throw new InvalidArgument(\sprintf('Missing parameter "Namespace" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Namespace'] = $v;
        if (null !== $v = $this->metricData) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MetricData.member.$index.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->entityMetricData) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["EntityMetricData.member.$index.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->strictEntityValidation) {
            $payload['StrictEntityValidation'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
