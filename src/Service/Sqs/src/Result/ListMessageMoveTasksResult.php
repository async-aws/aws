<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\ValueObject\ListMessageMoveTasksResultEntry;

class ListMessageMoveTasksResult extends Result
{
    /**
     * A list of message movement tasks and their attributes.
     *
     * @var ListMessageMoveTasksResultEntry[]
     */
    private $results;

    /**
     * @return ListMessageMoveTasksResultEntry[]
     */
    public function getResults(): array
    {
        $this->initialize();

        return $this->results;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->results = empty($data['Results']) ? [] : $this->populateResultListMessageMoveTasksResultEntryList($data['Results']);
    }

    private function populateResultListMessageMoveTasksResultEntry(array $json): ListMessageMoveTasksResultEntry
    {
        return new ListMessageMoveTasksResultEntry([
            'TaskHandle' => isset($json['TaskHandle']) ? (string) $json['TaskHandle'] : null,
            'Status' => isset($json['Status']) ? (string) $json['Status'] : null,
            'SourceArn' => isset($json['SourceArn']) ? (string) $json['SourceArn'] : null,
            'DestinationArn' => isset($json['DestinationArn']) ? (string) $json['DestinationArn'] : null,
            'MaxNumberOfMessagesPerSecond' => isset($json['MaxNumberOfMessagesPerSecond']) ? (int) $json['MaxNumberOfMessagesPerSecond'] : null,
            'ApproximateNumberOfMessagesMoved' => isset($json['ApproximateNumberOfMessagesMoved']) ? (int) $json['ApproximateNumberOfMessagesMoved'] : null,
            'ApproximateNumberOfMessagesToMove' => isset($json['ApproximateNumberOfMessagesToMove']) ? (int) $json['ApproximateNumberOfMessagesToMove'] : null,
            'FailureReason' => isset($json['FailureReason']) ? (string) $json['FailureReason'] : null,
            'StartedTimestamp' => isset($json['StartedTimestamp']) ? (int) $json['StartedTimestamp'] : null,
        ]);
    }

    /**
     * @return ListMessageMoveTasksResultEntry[]
     */
    private function populateResultListMessageMoveTasksResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultListMessageMoveTasksResultEntry($item);
        }

        return $items;
    }
}
