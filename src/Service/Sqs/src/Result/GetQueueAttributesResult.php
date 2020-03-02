<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetQueueAttributesResult extends Result
{
    private $Attributes = [];

    /**
     * A map of attributes to their respective values.
     *
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

        $this->Attributes = !$data->Attribute ? [] : (function (\SimpleXMLElement $xml): array {
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
