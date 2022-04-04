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

    private function populateResultUnprocessedTraceSegment(array $json): UnprocessedTraceSegment
    {
        return new UnprocessedTraceSegment([
            'Id' => isset($json['Id']) ? (string) $json['Id'] : null,
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return UnprocessedTraceSegment[]
     */
    private function populateResultUnprocessedTraceSegmentList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultUnprocessedTraceSegment($item);
        }

        return $items;
    }
}
