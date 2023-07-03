<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateThingGroupResponse extends Result
{
    /**
     * The thing group name.
     *
     * @var string|null
     */
    private $thingGroupName;

    /**
     * The thing group ARN.
     *
     * @var string|null
     */
    private $thingGroupArn;

    /**
     * The thing group ID.
     *
     * @var string|null
     */
    private $thingGroupId;

    public function getThingGroupArn(): ?string
    {
        $this->initialize();

        return $this->thingGroupArn;
    }

    public function getThingGroupId(): ?string
    {
        $this->initialize();

        return $this->thingGroupId;
    }

    public function getThingGroupName(): ?string
    {
        $this->initialize();

        return $this->thingGroupName;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->thingGroupName = isset($data['thingGroupName']) ? (string) $data['thingGroupName'] : null;
        $this->thingGroupArn = isset($data['thingGroupArn']) ? (string) $data['thingGroupArn'] : null;
        $this->thingGroupId = isset($data['thingGroupId']) ? (string) $data['thingGroupId'] : null;
    }
}
