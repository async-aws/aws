<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\DynamoDb\ValueObject\ThrottlingReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was denied due to request throttling. For detailed information about why the request was throttled and
 * the ARN of the impacted resource, find the ThrottlingReason [^1] field in the returned exception.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ThrottlingReason.html
 */
final class ThrottlingException extends ClientException
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

        $this->throttlingReasons = empty($data['throttlingReasons']) ? [] : $this->populateResultThrottlingReasonList($data['throttlingReasons']);
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
