<?php

namespace AsyncAws\TimestreamWrite\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\TimestreamWrite\ValueObject\RejectedRecord;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * WriteRecords would throw this exception in the following cases:.
 *
 * - Records with duplicate data where there are multiple records with the same dimensions, timestamps, and measure
 *   names but:
 *
 *   - Measure values are different
 *   - Version is not present in the request *or* the value of version in the new record is equal to or lower than the
 *     existing value
 *
 *   In this case, if Timestream rejects data, the `ExistingVersion` field in the `RejectedRecords` response will
 *   indicate the current record’s version. To force an update, you can resend the request with a version for the
 *   record set to a value greater than the `ExistingVersion`.
 * - Records with timestamps that lie outside the retention duration of the memory store
 * - Records with dimensions or measures that exceed the Timestream defined limits.
 *
 * For more information, see Quotas in the Timestream Developer Guide.
 *
 * @see https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html
 */
final class RejectedRecordsException extends ClientException
{
    private $rejectedRecords;

    /**
     * @return RejectedRecord[]
     */
    public function getRejectedRecords(): array
    {
        return $this->rejectedRecords;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->rejectedRecords = empty($data['RejectedRecords']) ? [] : $this->populateResultRejectedRecords($data['RejectedRecords']);
    }

    private function populateResultRejectedRecord(array $json): RejectedRecord
    {
        return new RejectedRecord([
            'RecordIndex' => isset($json['RecordIndex']) ? (int) $json['RecordIndex'] : null,
            'Reason' => isset($json['Reason']) ? (string) $json['Reason'] : null,
            'ExistingVersion' => isset($json['ExistingVersion']) ? (string) $json['ExistingVersion'] : null,
        ]);
    }

    /**
     * @return RejectedRecord[]
     */
    private function populateResultRejectedRecords(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRejectedRecord($item);
        }

        return $items;
    }
}
