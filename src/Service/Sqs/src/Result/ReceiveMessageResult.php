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
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ReceiveMessageResult;

        $this->messages = !$data->Message ? [] : $this->populateResultMessageList($data->Message);
    }

    /**
     * @return string[]
     */
    private function populateResultBinaryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->BinaryListValue as $item) {
            $a = ($v = $item) ? base64_decode((string) $v) : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    private function populateResultMessageBodyAttributeMap(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            if (null === $a = $item->Value) {
                continue;
            }
            $items[$item->Name->__toString()] = new MessageAttributeValue([
                'StringValue' => ($v = $a->StringValue) ? (string) $v : null,
                'BinaryValue' => ($v = $a->BinaryValue) ? base64_decode((string) $v) : null,
                'StringListValues' => !$a->StringListValue ? null : $this->populateResultStringList($a->StringListValue),
                'BinaryListValues' => !$a->BinaryListValue ? null : $this->populateResultBinaryList($a->BinaryListValue),
                'DataType' => (string) $a->DataType,
            ]);
        }

        return $items;
    }

    /**
     * @return Message[]
     */
    private function populateResultMessageList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new Message([
                'MessageId' => ($v = $item->MessageId) ? (string) $v : null,
                'ReceiptHandle' => ($v = $item->ReceiptHandle) ? (string) $v : null,
                'MD5OfBody' => ($v = $item->MD5OfBody) ? (string) $v : null,
                'Body' => ($v = $item->Body) ? (string) $v : null,
                'Attributes' => !$item->Attribute ? null : $this->populateResultMessageSystemAttributeMap($item->Attribute),
                'MD5OfMessageAttributes' => ($v = $item->MD5OfMessageAttributes) ? (string) $v : null,
                'MessageAttributes' => !$item->MessageAttribute ? null : $this->populateResultMessageBodyAttributeMap($item->MessageAttribute),
            ]);
        }

        return $items;
    }

    /**
     * @return array<MessageSystemAttributeName::*, string>
     */
    private function populateResultMessageSystemAttributeMap(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            if (null === $a = $item->Value) {
                continue;
            }
            $items[$item->Name->__toString()] = (string) $a;
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultStringList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->StringListValue as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
