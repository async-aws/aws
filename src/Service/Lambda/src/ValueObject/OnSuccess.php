<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * A destination for events that were processed successfully.
 *
 * To retain records of successful asynchronous invocations [^1], you can configure an Amazon SNS topic, Amazon SQS
 * queue, Lambda function, or Amazon EventBridge event bus as the destination.
 *
 * > `OnSuccess` is not supported in `CreateEventSourceMapping` or `UpdateEventSourceMapping` requests.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async.html#invocation-async-destinations
 */
final class OnSuccess
{
    /**
     * The Amazon Resource Name (ARN) of the destination resource.
     *
     * > Amazon SNS destinations have a message size limit of 256 KB. If the combined size of the function request and
     * > response payload exceeds the limit, Lambda will drop the payload when sending `OnFailure` event to the destination.
     * > For details on this behavior, refer to Retaining records of asynchronous invocations [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async-retain-records.html
     *
     * @var string|null
     */
    private $destination;

    /**
     * @param array{
     *   Destination?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destination = $input['Destination'] ?? null;
    }

    /**
     * @param array{
     *   Destination?: string|null,
     * }|OnSuccess $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }
}
