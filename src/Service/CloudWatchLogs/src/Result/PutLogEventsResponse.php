<?php

namespace AsyncAws\CloudWatchLogs\Result;

use AsyncAws\CloudWatchLogs\ValueObject\RejectedLogEventsInfo;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutLogEventsResponse extends Result
{
    /**
     * The next sequence token.
     *
     * ! This field has been deprecated.
     * !
     * ! The sequence token is now ignored in `PutLogEvents` actions. `PutLogEvents` actions are always accepted even if the
     * ! sequence token is not valid. You can use parallel `PutLogEvents` actions on the same log stream and you do not need
     * ! to wait for the response of a previous `PutLogEvents` action to obtain the `nextSequenceToken` value.
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
