<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The output of the CreateThingType operation.
 */
class CreateThingTypeResponse extends Result
{
    /**
     * The name of the thing type.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * The Amazon Resource Name (ARN) of the thing type.
     *
     * @var string|null
     */
    private $thingTypeArn;

    /**
     * The thing type ID.
     *
     * @var string|null
     */
    private $thingTypeId;

    public function getThingTypeArn(): ?string
    {
        $this->initialize();

        return $this->thingTypeArn;
    }

    public function getThingTypeId(): ?string
    {
        $this->initialize();

        return $this->thingTypeId;
    }

    public function getThingTypeName(): ?string
    {
        $this->initialize();

        return $this->thingTypeName;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->thingTypeName = isset($data['thingTypeName']) ? (string) $data['thingTypeName'] : null;
        $this->thingTypeArn = isset($data['thingTypeArn']) ? (string) $data['thingTypeArn'] : null;
        $this->thingTypeId = isset($data['thingTypeId']) ? (string) $data['thingTypeId'] : null;
    }
}
