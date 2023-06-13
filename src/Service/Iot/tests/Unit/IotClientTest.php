<?php

namespace AsyncAws\Iot\Tests\Unit;

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
use AsyncAws\Iot\Result\AddThingToThingGroupResponse;
use AsyncAws\Iot\Result\CreateThingGroupResponse;
use AsyncAws\Iot\Result\CreateThingResponse;
use AsyncAws\Iot\Result\CreateThingTypeResponse;
use AsyncAws\Iot\Result\DeleteThingGroupResponse;
use AsyncAws\Iot\Result\DeleteThingResponse;
use AsyncAws\Iot\Result\DeleteThingTypeResponse;
use AsyncAws\Iot\Result\ListThingGroupsForThingResponse;
use AsyncAws\Iot\Result\ListThingGroupsResponse;
use AsyncAws\Iot\Result\ListThingsInThingGroupResponse;
use AsyncAws\Iot\Result\ListThingsResponse;
use AsyncAws\Iot\Result\ListThingTypesResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class IotClientTest extends TestCase
{
    public function testAddThingToThingGroup(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new AddThingToThingGroupRequest([
        ]);
        $result = $client->addThingToThingGroup($input);

        self::assertInstanceOf(AddThingToThingGroupResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateThing(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateThingRequest([
            'thingName' => 'change me',
        ]);
        $result = $client->createThing($input);

        self::assertInstanceOf(CreateThingResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateThingGroup(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateThingGroupRequest([
            'thingGroupName' => 'change me',
        ]);
        $result = $client->createThingGroup($input);

        self::assertInstanceOf(CreateThingGroupResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateThingType(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateThingTypeRequest([
            'thingTypeName' => 'change me',
        ]);
        $result = $client->createThingType($input);

        self::assertInstanceOf(CreateThingTypeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteThing(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteThingRequest([
            'thingName' => 'change me',
        ]);
        $result = $client->deleteThing($input);

        self::assertInstanceOf(DeleteThingResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteThingGroup(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteThingGroupRequest([
            'thingGroupName' => 'change me',
        ]);
        $result = $client->deleteThingGroup($input);

        self::assertInstanceOf(DeleteThingGroupResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteThingType(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteThingTypeRequest([
            'thingTypeName' => 'change me',
        ]);
        $result = $client->deleteThingType($input);

        self::assertInstanceOf(DeleteThingTypeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListThingGroups(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new ListThingGroupsRequest([
        ]);
        $result = $client->listThingGroups($input);

        self::assertInstanceOf(ListThingGroupsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListThingGroupsForThing(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new ListThingGroupsForThingRequest([
            'thingName' => 'change me',
        ]);
        $result = $client->listThingGroupsForThing($input);

        self::assertInstanceOf(ListThingGroupsForThingResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListThingTypes(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new ListThingTypesRequest([
        ]);
        $result = $client->listThingTypes($input);

        self::assertInstanceOf(ListThingTypesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListThings(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new ListThingsRequest([
        ]);
        $result = $client->listThings($input);

        self::assertInstanceOf(ListThingsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListThingsInThingGroup(): void
    {
        $client = new IotClient([], new NullProvider(), new MockHttpClient());

        $input = new ListThingsInThingGroupRequest([
            'thingGroupName' => 'change me',
        ]);
        $result = $client->listThingsInThingGroup($input);

        self::assertInstanceOf(ListThingsInThingGroupResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
