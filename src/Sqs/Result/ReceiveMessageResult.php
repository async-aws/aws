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

        $this->Messages = (static function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new Message([
                    'MessageId' => static::xmlValueOrNull($item->MessageId, 'string'),
                    'ReceiptHandle' => static::xmlValueOrNull($item->ReceiptHandle, 'string'),
                    'MD5OfBody' => static::xmlValueOrNull($item->MD5OfBody, 'string'),
                    'Body' => static::xmlValueOrNull($item->Body, 'string'),
                    'Attributes' => (static function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = static::xmlValueOrNull($item->Value, 'string');
                        }

                        return $items;
                    })($item->Attribute),
                    'MD5OfMessageAttributes' => static::xmlValueOrNull($item->MD5OfMessageAttributes, 'string'),
                    'MessageAttributes' => (static function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $items[$item->Name->__toString()] = new MessageAttributeValue([
                                'StringValue' => static::xmlValueOrNull($item->Value->StringValue, 'string'),
                                'BinaryValue' => static::xmlValueOrNull($item->Value->BinaryValue, 'string'),
                                'StringListValues' => (static function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->StringListValue as $item) {
                                        $items[] = static::xmlValueOrNull($item, 'string');
                                    }

                                    return $items;
                                })($item->Value->StringListValue),
                                'BinaryListValues' => (static function (\SimpleXMLElement $xml): array {
                                    $items = [];
                                    foreach ($xml->BinaryListValue as $item) {
                                        $items[] = static::xmlValueOrNull($item, 'string');
                                    }

                                    return $items;
                                })($item->Value->BinaryListValue),
                                'DataType' => static::xmlValueOrNull($item->Value->DataType, 'string'),
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
