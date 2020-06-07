<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;
use AsyncAws\Sqs\ValueObject\Message;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;

class ReceiveMessageResult extends Result
{
    /**
     * A list of messages.
     */
    private $Messages = [];

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        $this->initialize();

        return $this->Messages;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ReceiveMessageResult;

        $this->Messages = !$data->Message ? [] : $this->populateResultMessageList($data->Message);
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
            $items[$item->Name->__toString()] = new MessageAttributeValue([
                'StringValue' => ($v = $item->Value->StringValue) ? (string) $v : null,
                'BinaryValue' => ($v = $item->Value->BinaryValue) ? base64_decode((string) $v) : null,
                'StringListValues' => !$item->Value->StringListValue ? [] : $this->populateResultStringList($item->Value->StringListValue),
                'BinaryListValues' => !$item->Value->BinaryListValue ? [] : $this->populateResultBinaryList($item->Value->BinaryListValue),
                'DataType' => (string) $item->Value->DataType,
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
                'Attributes' => !$item->Attribute ? [] : $this->populateResultMessageSystemAttributeMap($item->Attribute),
                'MD5OfMessageAttributes' => ($v = $item->MD5OfMessageAttributes) ? (string) $v : null,
                'MessageAttributes' => !$item->MessageAttribute ? [] : $this->populateResultMessageBodyAttributeMap($item->MessageAttribute),
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
            $a = (string) $item->Value;
            if (null !== $a) {
                $items[$item->Name->__toString()] = $a;
            }
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
