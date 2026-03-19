<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * A destination for events that failed processing. For more information, see Adding a destination [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async-retain-records.html#invocation-async-destinations
 */
final class OnFailure
{
    /**
     * The Amazon Resource Name (ARN) of the destination resource.
     *
     * To retain records of failed invocations from Kinesis [^1], DynamoDB [^2], self-managed Apache Kafka [^3], or Amazon
     * MSK [^4], you can configure an Amazon SNS topic, Amazon SQS queue, Amazon S3 bucket, or Kafka topic as the
     * destination.
     *
     * > Amazon SNS destinations have a message size limit of 256 KB. If the combined size of the function request and
     * > response payload exceeds the limit, Lambda will drop the payload when sending `OnFailure` event to the destination.
     * > For details on this behavior, refer to Retaining records of asynchronous invocations [^5].
     *
     * To retain records of failed invocations from Kinesis [^6], DynamoDB [^7], self-managed Kafka [^8] or Amazon MSK [^9],
     * you can configure an Amazon SNS topic, Amazon SQS queue, or Amazon S3 bucket as the destination.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/with-kinesis.html
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/with-ddb.html
     * [^3]: https://docs.aws.amazon.com/lambda/latest/dg/kafka-on-failure.html
     * [^4]: https://docs.aws.amazon.com/lambda/latest/dg/kafka-on-failure.html
     * [^5]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async-retain-records.html
     * [^6]: https://docs.aws.amazon.com/lambda/latest/dg/with-kinesis.html
     * [^7]: https://docs.aws.amazon.com/lambda/latest/dg/with-ddb.html
     * [^8]: https://docs.aws.amazon.com/lambda/latest/dg/with-kafka.html#services-smaa-onfailure-destination
     * [^9]: https://docs.aws.amazon.com/lambda/latest/dg/with-msk.html#services-msk-onfailure-destination
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
     * }|OnFailure $input
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
