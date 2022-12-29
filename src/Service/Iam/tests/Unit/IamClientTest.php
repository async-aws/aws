<?php

namespace AsyncAws\Iam\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\AddUserToGroupRequest;
use AsyncAws\Iam\Input\CreateAccessKeyRequest;
use AsyncAws\Iam\Input\CreateServiceSpecificCredentialRequest;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteAccessKeyRequest;
use AsyncAws\Iam\Input\DeleteUserPolicyRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\PutUserPolicyRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\Result\CreateAccessKeyResponse;
use AsyncAws\Iam\Result\CreateServiceSpecificCredentialResponse;
use AsyncAws\Iam\Result\CreateUserResponse;
use AsyncAws\Iam\Result\GetUserResponse;
use AsyncAws\Iam\Result\ListUsersResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class IamClientTest extends TestCase
{
    public function testAddUserToGroup(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new AddUserToGroupRequest([
            'GroupName' => 'change me',
            'UserName' => 'change me',
        ]);
        $result = $client->AddUserToGroup($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateAccessKey(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateAccessKeyRequest([

        ]);
        $result = $client->CreateAccessKey($input);

        self::assertInstanceOf(CreateAccessKeyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateServiceSpecificCredential(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateServiceSpecificCredentialRequest([
            'UserName' => 'test@async-aws.com',
            'ServiceName' => 'dynamodb.amazonaws.com',
        ]);
        $result = $client->createServiceSpecificCredential($input);

        self::assertInstanceOf(CreateServiceSpecificCredentialResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

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

    public function testDeleteAccessKey(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteAccessKeyRequest([

            'AccessKeyId' => 'change me',
        ]);
        $result = $client->DeleteAccessKey($input);

        self::assertInstanceOf(Result::class, $result);
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

    public function testDeleteUserPolicy(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteUserPolicyRequest([
            'UserName' => 'test@async-aws.com',
            'PolicyName' => 'Unrestricted Access',
        ]);
        $result = $client->deleteUserPolicy($input);

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

    public function testPutUserPolicy(): void
    {
        $client = new IamClient([], new NullProvider(), new MockHttpClient());

        $input = new PutUserPolicyRequest([
            'UserName' => 'test@async-aws.com',
            'PolicyName' => 'Unrestricted Access',
            'PolicyDocument' => '{"Version":"2012-10-17","Statement":{"Effect":"Allow","Action":"*","Resource":"*"}}',
        ]);
        $result = $client->putUserPolicy($input);

        self::assertInstanceOf(Result::class, $result);
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
