<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The output of the CreateThing operation.
 */
class CreateThingResponse extends Result
{
    /**
     * The name of the new thing.
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The ARN of the new thing.
     *
     * @var string|null
     */
    private $thingArn;

    /**
     * The thing ID.
     *
     * @var string|null
     */
    private $thingId;

    public function getThingArn(): ?string
    {
        $this->initialize();

        return $this->thingArn;
    }

    public function getThingId(): ?string
    {
        $this->initialize();

        return $this->thingId;
    }

    public function getThingName(): ?string
    {
        $this->initialize();

        return $this->thingName;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->thingName = isset($data['thingName']) ? (string) $data['thingName'] : null;
        $this->thingArn = isset($data['thingArn']) ? (string) $data['thingArn'] : null;
        $this->thingId = isset($data['thingId']) ? (string) $data['thingId'] : null;
    }
}
