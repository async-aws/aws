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
        $this->rejectedLogEventsInfo = empty($data['rejectedLogEventsInfo']) ? null : new RejectedLogEventsInfo([
            'tooNewLogEventStartIndex' => isset($data['rejectedLogEventsInfo']['tooNewLogEventStartIndex']) ? (int) $data['rejectedLogEventsInfo']['tooNewLogEventStartIndex'] : null,
            'tooOldLogEventEndIndex' => isset($data['rejectedLogEventsInfo']['tooOldLogEventEndIndex']) ? (int) $data['rejectedLogEventsInfo']['tooOldLogEventEndIndex'] : null,
            'expiredLogEventEndIndex' => isset($data['rejectedLogEventsInfo']['expiredLogEventEndIndex']) ? (int) $data['rejectedLogEventsInfo']['expiredLogEventEndIndex'] : null,
        ]);
    }
}
