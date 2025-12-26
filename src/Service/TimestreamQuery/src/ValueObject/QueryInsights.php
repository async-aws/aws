<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamQuery\Enum\QueryInsightsMode;

/**
 * `QueryInsights` is a performance tuning feature that helps you optimize your queries, reducing costs and improving
 * performance. With `QueryInsights`, you can assess the pruning efficiency of your queries and identify areas for
 * improvement to enhance query performance. With `QueryInsights`, you can also analyze the effectiveness of your
 * queries in terms of temporal and spatial pruning, and identify opportunities to improve performance. Specifically,
 * you can evaluate how well your queries use time-based and partition key-based indexing strategies to optimize data
 * retrieval. To optimize query performance, it's essential that you fine-tune both the temporal and spatial parameters
 * that govern query execution.
 *
 * The key metrics provided by `QueryInsights` are `QuerySpatialCoverage` and `QueryTemporalRange`.
 * `QuerySpatialCoverage` indicates how much of the spatial axis the query scans, with lower values being more
 * efficient. `QueryTemporalRange` shows the time range scanned, with narrower ranges being more performant.
 *
 * **Benefits of QueryInsights**
 *
 * The following are the key benefits of using `QueryInsights`:
 *
 * - **Identifying inefficient queries** – `QueryInsights` provides information on the time-based and attribute-based
 *   pruning of the tables accessed by the query. This information helps you identify the tables that are sub-optimally
 *   accessed.
 * - **Optimizing your data model and partitioning** – You can use the `QueryInsights` information to access and
 *   fine-tune your data model and partitioning strategy.
 * - **Tuning queries** – `QueryInsights` highlights opportunities to use indexes more effectively.
 *
 * > The maximum number of `Query` API requests you're allowed to make with `QueryInsights` enabled is 1 query per
 * > second (QPS). If you exceed this query rate, it might result in throttling.
 */
final class QueryInsights
{
    /**
     * Provides the following modes to enable `QueryInsights`:
     *
     * - `ENABLED_WITH_RATE_CONTROL` – Enables `QueryInsights` for the queries being processed. This mode also includes a
     *   rate control mechanism, which limits the `QueryInsights` feature to 1 query per second (QPS).
     * - `DISABLED` – Disables `QueryInsights`.
     *
     * @var QueryInsightsMode::*
     */
    private $mode;

    /**
     * @param array{
     *   Mode: QueryInsightsMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? $this->throwException(new InvalidArgument('Missing required field "Mode".'));
    }

    /**
     * @param array{
     *   Mode: QueryInsightsMode::*,
     * }|QueryInsights $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return QueryInsightsMode::*
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->mode;
        if (!QueryInsightsMode::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "Mode" for "%s". The value "%s" is not a valid "QueryInsightsMode".', __CLASS__, $v));
        }
        $payload['Mode'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
