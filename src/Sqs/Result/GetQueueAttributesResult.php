<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetQueueAttributesResult extends Result
{
    /**
     * A map of attributes to their respective values.
     */
    private $Attributes = [];

    /**
     * Ensure current request is resolved and right exception is thrown.
     */
    public function __destruct()
    {
        $this->resolve();
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->Attributes;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->GetQueueAttributesResult;

        $this->Attributes = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $a = ($v = $item->Value) ? (string) $v : null;
                if (null !== $a) {
                    $items[$item->Name->__toString()] = $a;
                }
            }

            return $items;
        })($data->Attribute);
    }
}
