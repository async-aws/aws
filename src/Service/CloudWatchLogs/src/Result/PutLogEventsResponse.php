<?php

namespace AsyncAws\CloudWatchLogs\Result;

use AsyncAws\CloudWatchLogs\ValueObject\RejectedLogEventsInfo;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutLogEventsResponse extends Result
{
    /**
     * The next sequence token.
     */
    private $nextSequenceToken;

    /**
     * The rejected events.
     */
    private $rejectedLogEventsInfo;

    public function getNextSequenceToken(): ?string
    {
        $this->initialize();

        return $this->nextSequenceToken;
    }

    public function getRejectedLogEventsInfo(): ?RejectedLogEventsInfo
    {
        $this->initialize();

        return $this->rejectedLogEventsInfo;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextSequenceToken = isset($data['nextSequenceToken']) ? (string) $data['nextSequenceToken'] : null;
        $this->rejectedLogEventsInfo = empty($data['rejectedLogEventsInfo']) ? null : $this->populateResultRejectedLogEventsInfo($data['rejectedLogEventsInfo']);
    }

    private function populateResultRejectedLogEventsInfo(array $json): RejectedLogEventsInfo
    {
        return new RejectedLogEventsInfo([
            'tooNewLogEventStartIndex' => isset($json['tooNewLogEventStartIndex']) ? (int) $json['tooNewLogEventStartIndex'] : null,
            'tooOldLogEventEndIndex' => isset($json['tooOldLogEventEndIndex']) ? (int) $json['tooOldLogEventEndIndex'] : null,
            'expiredLogEventEndIndex' => isset($json['expiredLogEventEndIndex']) ? (int) $json['expiredLogEventEndIndex'] : null,
        ]);
    }
}
