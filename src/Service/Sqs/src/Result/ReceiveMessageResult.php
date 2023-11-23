<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;
use AsyncAws\Sqs\ValueObject\Message;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;

/**
 * A list of received messages.
 */
class ReceiveMessageResult extends Result
{
    /**
     * A list of messages.
     *
     * @var Message[]
     */
    private $messages;

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        $this->initialize();

        return $this->messages;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->messages = empty($data['Messages']) ? [] : $this->populateResultMessageList($data['Messages']);
    }

    /**
     * @return string[]
     */
    private function populateResultBinaryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? base64_decode((string) $item) : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultMessage(array $json): Message
    {
        return new Message([
            'MessageId' => isset($json['MessageId']) ? (string) $json['MessageId'] : null,
            'ReceiptHandle' => isset($json['ReceiptHandle']) ? (string) $json['ReceiptHandle'] : null,
            'MD5OfBody' => isset($json['MD5OfBody']) ? (string) $json['MD5OfBody'] : null,
            'Body' => isset($json['Body']) ? (string) $json['Body'] : null,
            'Attributes' => !isset($json['Attributes']) ? null : $this->populateResultMessageSystemAttributeMap($json['Attributes']),
            'MD5OfMessageAttributes' => isset($json['MD5OfMessageAttributes']) ? (string) $json['MD5OfMessageAttributes'] : null,
            'MessageAttributes' => !isset($json['MessageAttributes']) ? null : $this->populateResultMessageBodyAttributeMap($json['MessageAttributes']),
        ]);
    }

    private function populateResultMessageAttributeValue(array $json): MessageAttributeValue
    {
        return new MessageAttributeValue([
            'StringValue' => isset($json['StringValue']) ? (string) $json['StringValue'] : null,
            'BinaryValue' => isset($json['BinaryValue']) ? base64_decode((string) $json['BinaryValue']) : null,
            'StringListValues' => !isset($json['StringListValues']) ? null : $this->populateResultStringList($json['StringListValues']),
            'BinaryListValues' => !isset($json['BinaryListValues']) ? null : $this->populateResultBinaryList($json['BinaryListValues']),
            'DataType' => (string) $json['DataType'],
        ]);
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    private function populateResultMessageBodyAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultMessageAttributeValue($value);
        }

        return $items;
    }

    /**
     * @return Message[]
     */
    private function populateResultMessageList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMessage($item);
        }

        return $items;
    }

    /**
     * @return array<MessageSystemAttributeName::*, string>
     */
    private function populateResultMessageSystemAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultStringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
