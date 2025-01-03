<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The batch request contains more entries than permissible. For Amazon SQS, the maximum number of entries you can
 * include in a single SendMessageBatch [^1], DeleteMessageBatch [^2], or ChangeMessageVisibilityBatch [^3] request is
 * 10.
 *
 * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html
 * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessageBatch.html
 * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibilityBatch.html
 */
final class TooManyEntriesInBatchRequestException extends ClientException
{
}
