<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->ReceiveMessageResult;

        $this->Messages = !$data->Message ? [] : (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml->member as $item) {
                $items[] = new Message([
                    'MessageId' => ($v = $item->MessageId) ? (string) $v : null,
                    'ReceiptHandle' => ($v = $item->ReceiptHandle) ? (string) $v : null,
                    'MD5OfBody' => ($v = $item->MD5OfBody) ? (string) $v : null,
                    'Body' => ($v = $item->Body) ? (string) $v : null,
                    'Attributes' => !$item->Attribute ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $a = (string) $item->Value;
                            if (null !== $a) {
                                $items[$item->Name->__toString()] = $a;
                            }
                        }

                        return $items;
                    })($item->Attribute),
                    'MD5OfMessageAttributes' => ($v = $item->MD5OfMessageAttributes) ? (string) $v : null,
                    'MessageAttributes' => !$item->MessageAttribute ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = new MessageAttributeValue([
                                'StringValue' => ($v = $item->Value->StringValue) ? (string) $v : null,
                                'BinaryValue' => ($v = $item->Value->BinaryValue) ? base64_decode((string) $v) : null,
                                'StringListValues' => !$item->Value->StringListValue ? [] : (function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->StringListValue->member as $item) {
                                        $a = (string) $item;
                                        if (null !== $a) {
                                            $items[] = $a;
                                        }
                                    }

                                    return $items;
                                })($item->Value->StringListValue),
                                'BinaryListValues' => !$item->Value->BinaryListValue ? [] : (function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->BinaryListValue->member as $item) {
                                        $a = base64_decode((string) $item);
                                        if (null !== $a) {
                                            $items[] = $a;
                                        }
                                    }

                                    return $items;
                                })($item->Value->BinaryListValue),
                                'DataType' => (string) $item->Value->DataType,
                            ]);
                        }

                        return $items;
                    })($item->MessageAttribute),
                ]);
            }

            return $items;
        })($data->Message);
    }
}
