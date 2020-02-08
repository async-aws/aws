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
            if (0 === $xml->count() || 0 === $xml->Message->count()) {
                return [];
            }
            $items = [];
            foreach ($xml->Message as $item) {
                $items[] = new Message([
                    'MessageId' => $this->xmlValueOrNull($item->MessageId, 'string'),
                    'ReceiptHandle' => $this->xmlValueOrNull($item->ReceiptHandle, 'string'),
                    'MD5OfBody' => $this->xmlValueOrNull($item->MD5OfBody, 'string'),
                    'Body' => $this->xmlValueOrNull($item->Body, 'string'),
                    'Attributes' => (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = $this->xmlValueOrNull($item->Value, 'string');
                        }

                        return $items;
                    })($item->Attribute),
                    'MD5OfMessageAttributes' => $this->xmlValueOrNull($item->MD5OfMessageAttributes, 'string'),
                    'MessageAttributes' => (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = new MessageAttributeValue([
                                'StringValue' => $this->xmlValueOrNull($item->Value->StringValue, 'string'),
                                'BinaryValue' => $this->xmlValueOrNull($item->Value->BinaryValue, 'string'),
                                'StringListValues' => (function (\SimpleXMLElement $xml): array {
                                    if (0 === $xml->count() || 0 === $xml->StringListValue->count()) {
                                        return [];
                                    }
                                    $items = [];
                                    foreach ($xml->StringListValue as $item) {
                                        $items[] = $this->xmlValueOrNull($item, 'string');
                                    }

                                    return $items;
                                })($item->Value->StringListValue),
                                'BinaryListValues' => (function (\SimpleXMLElement $xml): array {
                                    if (0 === $xml->count() || 0 === $xml->BinaryListValue->count()) {
                                        return [];
                                    }
                                    $items = [];
                                    foreach ($xml->BinaryListValue as $item) {
                                        $items[] = $this->xmlValueOrNull($item, 'string');
                                    }

                                    return $items;
                                })($item->Value->BinaryListValue),
                                'DataType' => $this->xmlValueOrNull($item->Value->DataType, 'string'),
                            ]);
                        }

                        return $items;
                    })($item->MessageAttribute),
                ]);
            }

            return $items;
        })($data->Messages);
    }
}
