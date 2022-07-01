<?php

namespace AsyncAws\Iot\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\AddThingToThingGroupRequest;
use AsyncAws\Iot\Input\CreateThingGroupRequest;
use AsyncAws\Iot\Input\CreateThingRequest;
use AsyncAws\Iot\Input\CreateThingTypeRequest;
use AsyncAws\Iot\Input\DeleteThingGroupRequest;
use AsyncAws\Iot\Input\DeleteThingRequest;
use AsyncAws\Iot\Input\DeleteThingTypeRequest;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;
use AsyncAws\Iot\Input\ListThingGroupsRequest;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;
use AsyncAws\Iot\Input\ListThingsRequest;
use AsyncAws\Iot\Input\ListThingTypesRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\ValueObject\AttributePayload;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingGroupProperties;
use AsyncAws\Iot\ValueObject\ThingTypeProperties;

class IotClientTest extends TestCase
{
    public function testAddThingToThingGroup(): void
    {
        $client = $this->getClient();

        $input = new AddThingToThingGroupRequest([
            'thingGroupName' => 'change me',
            'thingGroupArn' => 'change me',
            'thingName' => 'change me',
            'thingArn' => 'change me',
            'overrideDynamicGroups' => false,
        ]);
        $result = $client->addThingToThingGroup($input);

        $result->resolve();
    }

    public function testCreateThing(): void
    {
        $client = $this->getClient();

        $input = new CreateThingRequest([
            'thingName' => 'change me',
            'thingTypeName' => 'change me',
            'attributePayload' => new AttributePayload([
                'attributes' => ['change me' => 'change me'],
                'merge' => false,
            ]),
            'billingGroupName' => 'change me',
        ]);
        $result = $client->createThing($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getthingName());
        self::assertSame('changeIt', $result->getthingArn());
        self::assertSame('changeIt', $result->getthingId());
    }

    public function testCreateThingGroup(): void
    {
        $client = $this->getClient();

        $input = new CreateThingGroupRequest([
            'thingGroupName' => 'change me',
            'parentGroupName' => 'change me',
            'thingGroupProperties' => new ThingGroupProperties([
                'thingGroupDescription' => 'change me',
                'attributePayload' => new AttributePayload([
                    'attributes' => ['change me' => 'change me'],
                    'merge' => false,
                ]),
            ]),
            'tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);
        $result = $client->createThingGroup($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getthingGroupName());
        self::assertSame('changeIt', $result->getthingGroupArn());
        self::assertSame('changeIt', $result->getthingGroupId());
    }

    public function testCreateThingType(): void
    {
        $client = $this->getClient();

        $input = new CreateThingTypeRequest([
            'thingTypeName' => 'change me',
            'thingTypeProperties' => new ThingTypeProperties([
                'thingTypeDescription' => 'change me',
                'searchableAttributes' => ['change me'],
            ]),
            'tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);
        $result = $client->createThingType($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getthingTypeName());
        self::assertSame('changeIt', $result->getthingTypeArn());
        self::assertSame('changeIt', $result->getthingTypeId());
    }

    public function testDeleteThing(): void
    {
        $client = $this->getClient();

        $input = new DeleteThingRequest([
            'thingName' => 'change me',
            'expectedVersion' => 1337,
        ]);
        $result = $client->deleteThing($input);

        $result->resolve();
    }

    public function testDeleteThingGroup(): void
    {
        $client = $this->getClient();

        $input = new DeleteThingGroupRequest([
            'thingGroupName' => 'change me',
            'expectedVersion' => 1337,
        ]);
        $result = $client->deleteThingGroup($input);

        $result->resolve();
    }

    public function testDeleteThingType(): void
    {
        $client = $this->getClient();

        $input = new DeleteThingTypeRequest([
            'thingTypeName' => 'change me',
        ]);
        $result = $client->deleteThingType($input);

        $result->resolve();
    }

    public function testListThingGroups(): void
    {
        $client = $this->getClient();

        $input = new ListThingGroupsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'parentGroup' => 'change me',
            'namePrefixFilter' => 'change me',
            'recursive' => false,
        ]);
        $result = $client->listThingGroups($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getthingGroups());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testListThingGroupsForThing(): void
    {
        $client = $this->getClient();

        $input = new ListThingGroupsForThingRequest([
            'thingName' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);
        $result = $client->listThingGroupsForThing($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getthingGroups());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testListThingTypes(): void
    {
        $client = $this->getClient();

        $input = new ListThingTypesRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'thingTypeName' => 'change me',
        ]);
        $result = $client->listThingTypes($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getthingTypes());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testListThings(): void
    {
        $client = $this->getClient();

        $input = new ListThingsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'attributeName' => 'change me',
            'attributeValue' => 'change me',
            'thingTypeName' => 'change me',
            'usePrefixAttributeValue' => false,
        ]);
        $result = $client->listThings($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getthings());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testListThingsInThingGroup(): void
    {
        $client = $this->getClient();

        $input = new ListThingsInThingGroupRequest([
            'thingGroupName' => 'change me',
            'recursive' => false,
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);
        $result = $client->listThingsInThingGroup($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getthings());
        self::assertSame('changeIt', $result->getnextToken());
    }

    private function getClient(): IotClient
    {
        self::markTestSkipped('There is no free docker image available for Iot.');

        return new IotClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
