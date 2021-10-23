<?php

namespace AsyncAws\XRay\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\XRay\ValueObject\UnprocessedTraceSegment;

class PutTraceSegmentsResult extends Result
{
    /**
     * Segments that failed processing.
     */
    private $unprocessedTraceSegments;

    /**
     * @return UnprocessedTraceSegment[]
     */
    public function getUnprocessedTraceSegments(): array
    {
        $this->initialize();

        return $this->unprocessedTraceSegments;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->unprocessedTraceSegments = empty($data['UnprocessedTraceSegments']) ? [] : $this->populateResultUnprocessedTraceSegmentList($data['UnprocessedTraceSegments']);
    }

    /**
     * @return UnprocessedTraceSegment[]
     */
    private function populateResultUnprocessedTraceSegmentList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new UnprocessedTraceSegment([
                'Id' => isset($item['Id']) ? (string) $item['Id'] : null,
                'ErrorCode' => isset($item['ErrorCode']) ? (string) $item['ErrorCode'] : null,
                'Message' => isset($item['Message']) ? (string) $item['Message'] : null,
            ]);
        }

        return $items;
    }
}
