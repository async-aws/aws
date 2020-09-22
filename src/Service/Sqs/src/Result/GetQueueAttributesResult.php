<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\QueueAttributeName;

class GetQueueAttributesResult extends Result
{
    /**
     * A map of attributes to their respective values.
     */
    private $Attributes = [];

    /**
     * @return array<QueueAttributeName::*, string>
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->Attributes;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
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
