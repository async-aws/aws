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

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->ReceiveMessageResult;

        $this->Messages = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new Message([
                    'MessageId' => ($v = $item->MessageId) ? (string) $v : null,
                    'ReceiptHandle' => ($v = $item->ReceiptHandle) ? (string) $v : null,
                    'MD5OfBody' => ($v = $item->MD5OfBody) ? (string) $v : null,
                    'Body' => ($v = $item->Body) ? (string) $v : null,
                    'Attributes' => (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = ($v = $item->Value) ? (string) $v : null;
                        }

                        return $items;
                    })($item->Attribute),
                    'MD5OfMessageAttributes' => ($v = $item->MD5OfMessageAttributes) ? (string) $v : null,
                    'MessageAttributes' => (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = new MessageAttributeValue([
                                'StringValue' => ($v = $item->Value->StringValue) ? (string) $v : null,
                                'BinaryValue' => ($v = $item->Value->BinaryValue) ? base64_decode((string) $v) : null,
                                'StringListValues' => (function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->StringListValue as $item) {
                                        $items[] = ($v = $item) ? (string) $v : null;
                                    }

                                    return $items;
                                })($item->Value->StringListValue),
                                'BinaryListValues' => (function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->BinaryListValue as $item) {
                                        $items[] = ($v = $item) ? base64_decode((string) $v) : null;
                                    }

                                    return $items;
                                })($item->Value->BinaryListValue),
                                'DataType' => ($v = $item->Value->DataType) ? (string) $v : null,
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
