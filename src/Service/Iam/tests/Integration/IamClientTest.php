<?php

namespace AsyncAws\Iam\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\ValueObject\Tag;

class IamClientTest extends TestCase
{
    public function testCreateUser(): void
    {
        $client = $this->getClient();

        $input = new CreateUserRequest([
            'Path' => 'change me',
            'UserName' => 'change me',
            'PermissionsBoundary' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);
        $result = $client->CreateUser($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getUser());
    }

    public function testDeleteUser(): void
    {
        $client = $this->getClient();

        $input = new DeleteUserRequest([
            'UserName' => 'change me',
        ]);
        $result = $client->DeleteUser($input);

        $result->resolve();
    }

    public function testGetUser(): void
    {
        $client = $this->getClient();

        $input = new GetUserRequest([
            'UserName' => 'change me',
        ]);
        $result = $client->GetUser($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getUser());
    }

    public function testListUsers(): void
    {
        $client = $this->getClient();

        $input = new ListUsersRequest([
            'PathPrefix' => 'change me',
            'Marker' => 'change me',
            'MaxItems' => 1337,
        ]);
        $result = $client->ListUsers($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getUsers());
        self::assertFalse($result->getIsTruncated());
        self::assertSame('changeIt', $result->getMarker());
    }

    public function testUpdateUser(): void
    {
        $client = $this->getClient();

        $input = new UpdateUserRequest([
            'UserName' => 'change me',
            'NewPath' => 'change me',
            'NewUserName' => 'change me',
        ]);
        $result = $client->UpdateUser($input);

        $result->resolve();
    }

    private function getClient(): IamClient
    {
        self::markTestSkipped('No Docker image for IAM');

        return new IamClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
