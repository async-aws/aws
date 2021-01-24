<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\QueueAttributeName;

/**
 * A list of returned queue attributes.
 */
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

        $this->Attributes = !$data->Attribute ? [] : $this->populateResultQueueAttributeMap($data->Attribute);
    }

    /**
     * @return array<QueueAttributeName::*, string>
     */
    private function populateResultQueueAttributeMap(\SimpleXMLElement $xml): array
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
}
