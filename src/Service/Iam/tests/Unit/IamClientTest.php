<?php

namespace AsyncAws\Iam\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\Result\CreateUserResponse;
use AsyncAws\Iam\Result\GetUserResponse;
use AsyncAws\Iam\Result\ListUsersResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class IamClientTest extends TestCase
{
    public function testCreateUser(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateUserRequest([

            'UserName' => 'change me',

        ]);
        $result = $client->CreateUser($input);

        self::assertInstanceOf(CreateUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteUser(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteUserRequest([
            'UserName' => 'change me',
        ]);
        $result = $client->DeleteUser($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetUser(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new GetUserRequest([

        ]);
        $result = $client->GetUser($input);

        self::assertInstanceOf(GetUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListUsers(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new ListUsersRequest([

        ]);
        $result = $client->ListUsers($input);

        self::assertInstanceOf(ListUsersResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateUser(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateUserRequest([
            'UserName' => 'change me',

        ]);
        $result = $client->UpdateUser($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
