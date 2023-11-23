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
     *
     * @var array<QueueAttributeName::*, string>
     */
    private $attributes;

    /**
     * @return array<QueueAttributeName::*, string>
     */
    public function getAttributes(): array
    {
        $this->initialize();

        return $this->attributes;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->attributes = empty($data['Attributes']) ? [] : $this->populateResultQueueAttributeMap($data['Attributes']);
    }

    /**
     * @return array<QueueAttributeName::*, string>
     */
    private function populateResultQueueAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }
}
