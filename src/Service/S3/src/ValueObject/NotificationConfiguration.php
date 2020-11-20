<?php

namespace AsyncAws\S3\ValueObject;

final class NotificationConfiguration
{
    /**
     * The topic to which notifications are sent and the events for which notifications are generated.
     */
    private $TopicConfigurations;

    /**
     * The Amazon Simple Queue Service queues to publish messages to and the events for which to publish messages.
     */
    private $QueueConfigurations;

    /**
     * Describes the AWS Lambda functions to invoke and the events for which to invoke them.
     */
    private $LambdaFunctionConfigurations;

    /**
     * @param array{
     *   TopicConfigurations?: null|TopicConfiguration[],
     *   QueueConfigurations?: null|QueueConfiguration[],
     *   LambdaFunctionConfigurations?: null|LambdaFunctionConfiguration[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->TopicConfigurations = isset($input['TopicConfigurations']) ? array_map([TopicConfiguration::class, 'create'], $input['TopicConfigurations']) : null;
        $this->QueueConfigurations = isset($input['QueueConfigurations']) ? array_map([QueueConfiguration::class, 'create'], $input['QueueConfigurations']) : null;
        $this->LambdaFunctionConfigurations = isset($input['LambdaFunctionConfigurations']) ? array_map([LambdaFunctionConfiguration::class, 'create'], $input['LambdaFunctionConfigurations']) : null;
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
        return $this->LambdaFunctionConfigurations ?? [];
    }

    /**
     * @return QueueConfiguration[]
     */
    public function getQueueConfigurations(): array
    {
        return $this->QueueConfigurations ?? [];
    }

    /**
     * @return TopicConfiguration[]
     */
    public function getTopicConfigurations(): array
    {
        return $this->TopicConfigurations ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->TopicConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('TopicConfiguration'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->QueueConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('QueueConfiguration'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->LambdaFunctionConfigurations) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('CloudFunctionConfiguration'));

                $item->requestBody($child, $document);
            }
        }
    }
}
