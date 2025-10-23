<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\DynamoDb\ValueObject\ThrottlingReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was denied due to request throttling. For detailed information about why the request was throttled and
 * the ARN of the impacted resource, find the ThrottlingReason [^1] field in the returned exception. The Amazon Web
 * Services SDKs for DynamoDB automatically retry requests that receive this exception. Your request is eventually
 * successful, unless your retry queue is too large to finish. Reduce the frequency of requests and use exponential
 * backoff. For more information, go to Error Retries and Exponential Backoff [^2] in the *Amazon DynamoDB Developer
 * Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ThrottlingReason.html
 * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Programming.Errors.html#Programming.Errors.RetryAndBackoff
 */
final class ProvisionedThroughputExceededException extends ClientException
{
    /**
     * A list of ThrottlingReason [^1] that provide detailed diagnostic information about why the request was throttled.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ThrottlingReason.html
     *
     * @var ThrottlingReason[]
     */
    private $throttlingReasons;

    /**
     * @return ThrottlingReason[]
     */
    public function getThrottlingReasons(): array
    {
        return $this->throttlingReasons;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->throttlingReasons = empty($data['ThrottlingReasons']) ? [] : $this->populateResultThrottlingReasonList($data['ThrottlingReasons']);
    }

    private function populateResultThrottlingReason(array $json): ThrottlingReason
    {
        return new ThrottlingReason([
            'reason' => isset($json['reason']) ? (string) $json['reason'] : null,
            'resource' => isset($json['resource']) ? (string) $json['resource'] : null,
        ]);
    }

    /**
     * @return ThrottlingReason[]
     */
    private function populateResultThrottlingReasonList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultThrottlingReason($item);
        }

        return $items;
    }
}
