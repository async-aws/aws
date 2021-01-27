<?php

namespace AsyncAws\S3\ValueObject;

final class NotificationConfiguration
{
    /**
     * The topic to which notifications are sent and the events for which notifications are generated.
     */
    private $topicConfigurations;

    /**
     * The Amazon Simple Queue Service queues to publish messages to and the events for which to publish messages.
     */
    private $queueConfigurations;

    /**
     * Describes the AWS Lambda functions to invoke and the events for which to invoke them.
     */
    private $lambdaFunctionConfigurations;

    /**
     * @param array{
     *   TopicConfigurations?: null|TopicConfiguration[],
     *   QueueConfigurations?: null|QueueConfiguration[],
     *   LambdaFunctionConfigurations?: null|LambdaFunctionConfiguration[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->topicConfigurations = isset($input['TopicConfigurations']) ? array_map([TopicConfiguration::class, 'create'], $input['TopicConfigurations']) : null;
        $this->queueConfigurations = isset($input['QueueConfigurations']) ? array_map([QueueConfiguration::class, 'create'], $input['QueueConfigurations']) : null;
        $this->lambdaFunctionConfigurations = isset($input['LambdaFunctionConfigurations']) ? array_map([LambdaFunctionConfiguration::class, 'create'], $input['LambdaFunctionConfigurations']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return LambdaFunctionConfiguration[]
     */
    public function getLambdaFunctionConfigurations(): array
    {
        return $this->lambdaFunctionConfigurations ?? [];
    }

    /**
     * @return QueueConfiguration[]
     */
    public function getQueueConfigurations(): array
    {
        return $this->queueConfigurations ?? [];
    }

    /**
     * @return TopicConfiguration[]
     */
    public function getTopicConfigurations(): array
    {
        return $this->topicConfigurations ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->topicConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('TopicConfiguration'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->queueConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('QueueConfiguration'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->lambdaFunctionConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('CloudFunctionConfiguration'));

                $item->requestBody($child, $document);
            }
        }
    }
}
