<?php

namespace AsyncAws\Iam\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Enum\StatusType;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\AddUserToGroupRequest;
use AsyncAws\Iam\Input\CreateAccessKeyRequest;
use AsyncAws\Iam\Input\CreateServiceSpecificCredentialRequest;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteAccessKeyRequest;
use AsyncAws\Iam\Input\DeleteServiceSpecificCredentialRequest;
use AsyncAws\Iam\Input\DeleteUserPolicyRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListServiceSpecificCredentialsRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\PutUserPolicyRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\ValueObject\Tag;

class IamClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        try {
            $this->getClient()->createUser(['UserName' => 'jderusse', 'Path' => '/async-aws/']);
        } catch (ClientException $e) {
            if (409 !== $e->getCode()) {
                throw $e;
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->getClient()->deleteUser(['UserName' => 'jderusse']);
        } catch (ClientException $e) {
            if (404 !== $e->getCode()) {
                throw $e;
            }
        }
    }

    public function testAddUserToGroup(): void
    {
        self::markTestIncomplete('Needs to create a group in order to add a user into it');
        $client = $this->getClient();

        $input = new AddUserToGroupRequest([
            'GroupName' => 'change me',
            'UserName' => 'change me',
        ]);
        $result = $client->AddUserToGroup($input);

        $result->resolve();
    }

    public function testCreateAccessKey(): void
    {
        $client = $this->getClient();

        $input = new CreateAccessKeyRequest([
            'UserName' => 'jderusse',
        ]);
        $result = $client->CreateAccessKey($input);

        self::assertNotNull($result->getAccessKey());
        self::assertSame('jderusse', $result->getAccessKey()->getUserName());
        self::assertSame(StatusType::ACTIVE, $result->getAccessKey()->getStatus());
    }

    public function testCreateServiceSpecificCredential(): void
    {
        self::markTestSkipped('Localstack does not support the CreateServiceSpecificCredentialRequest API action');
        $client = $this->getClient();

        $input = new CreateServiceSpecificCredentialRequest([
            'UserName' => 'jderusse',
            'ServiceName' => 'codecommit.amazonaws.com',
        ]);
        $result = $client->createServiceSpecificCredential($input);

        self::assertSame(
        'jderusse',
        $result->getServiceSpecificCredential()->getUserName()
        );
        self::assertSame(
        'codecommit.amazonaws.com',
        $result->getServiceSpecificCredential()->getServiceName()
        );
    }

    public function testCreateUser(): void
    {
        $client = $this->getClient();

        $input = new CreateUserRequest([
            'Path' => '/engineering/root/',
            'UserName' => $username = uniqid('jderusse.', false),
            'PermissionsBoundary' => 'root',
            'Tags' => [new Tag([
                'Key' => 'demo',
                'Value' => 'yes',
            ])],
        ]);
        $result = $client->CreateUser($input);

        self::assertNotNull($result->getUser());
        self::assertSame('/engineering/root/', $result->getUser()->getPath());
        self::assertCount(1, $result->getUser()->getTags());
        self::assertSame('arn:aws:iam::000000000000:user/engineering/root/' . $username, $result->getUser()->getArn());
    }

    public function testDeleteAccessKey(): void
    {
        $client = $this->getClient();

        $result = $client->CreateAccessKey(['UserName' => 'jderusse']);

        $input = new DeleteAccessKeyRequest([
            'UserName' => 'jderusse',
            'AccessKeyId' => $result->getAccessKey()->getAccessKeyId(),
        ]);
        $client->DeleteAccessKey($input);
        self::expectNotToPerformAssertions();
    }

    public function testDeleteServiceSpecificCredential(): void
    {
        $client = $this->getClient();

        $client->createServiceSpecificCredential(new CreateServiceSpecificCredentialRequest([
            'UserName' => 'jderusse',
            'ServiceName' => 'codecommit.amazonaws.com',
        ]));

        $input = new ListServiceSpecificCredentialsRequest([
            'UserName' => 'jderusse',
        ]);
        $result = $client->listServiceSpecificCredentials($input);

        $result->resolve();

        $input = new DeleteServiceSpecificCredentialRequest([
            'UserName' => 'jderusse',
            'ServiceSpecificCredentialId' => $result->getServiceSpecificCredentials()[0]->getServiceSpecificCredentialId(),
        ]);
        $result = $client->deleteServiceSpecificCredential($input);

        $result->resolve();

        self::assertCount(0, $result->getServiceSpecificCredentials());
    }

    public function testDeleteUser(): void
    {
        $client = $this->getClient();

        $input = new DeleteUserRequest([
            'UserName' => 'jderusse',
        ]);
        $result = $client->DeleteUser($input);
        $result->resolve();

        self::expectExceptionCode(404);
        $client->getUser(['UserName' => 'jderusse']);
    }

    public function testDeleteUserPolicy(): void
    {
        $client = $this->getClient();

        $input = new PutUserPolicyRequest([
            'UserName' => 'jderusse',
            'PolicyName' => 'Access All Account Resources',
            'PolicyDocument' => '{"Version":"2012-10-17","Statement":{"Effect":"Allow","Action":"*","Resource":"*"}}',
        ]);
        $client->putUserPolicy($input);

        $input = new DeleteUserPolicyRequest([
            'UserName' => 'jderusse',
            'PolicyName' => 'Access All Account Resources',
        ]);
        $client->deleteUserPolicy($input);

        self::expectNotToPerformAssertions();
    }

    public function testGetUser(): void
    {
        $client = $this->getClient();

        $input = new GetUserRequest([
            'UserName' => 'jderusse',
        ]);
        $result = $client->GetUser($input);

        self::assertSame('jderusse', $result->getUser()->getUserName());
        self::assertSame('arn:aws:iam::000000000000:user/async-aws/jderusse', $result->getUser()->getArn());
    }

    public function testListServiceSpecificCredentials(): void
    {
        $client = $this->getClient();

        $client->createServiceSpecificCredential(new CreateServiceSpecificCredentialRequest([
            'UserName' => 'jderusse',
            'ServiceName' => 'codecommit.amazonaws.com',
        ]));

        $input = new ListServiceSpecificCredentialsRequest([
            'UserName' => 'jderusse',
        ]);
        $result = $client->listServiceSpecificCredentials($input);

        $result->resolve();

        self::assertCount(1, $result->getServiceSpecificCredentials());
        self::assertSame('codecommit.amazonaws.com', $result->getServiceSpecificCredentials()[0]->getServiceName());
    }

    public function testListUsers(): void
    {
        $client = $this->getClient();

        $input = new ListUsersRequest([
            'PathPrefix' => '/async-aws/',
        ]);
        $result = $client->ListUsers($input);

        self::assertCount(1, $users = iterator_to_array($result->getUsers()));
        self::assertSame('jderusse', $users[0]->getUserName());
        self::assertSame('arn:aws:iam::000000000000:user/async-aws/jderusse', $users[0]->getArn());
    }

    public function testPutUserPolicy(): void
    {
        $client = $this->getClient();

        $input = new PutUserPolicyRequest([
            'UserName' => 'jderusse',
            'PolicyName' => 'Access All Account Resources',
            'PolicyDocument' => '{"Version":"2012-10-17","Statement":{"Effect":"Allow","Action":"*","Resource":"*"}}',
        ]);
        $client->putUserPolicy($input);

        self::expectNotToPerformAssertions();

        $client->deleteUserPolicy(new DeleteUserPolicyRequest([
            'UserName' => 'jderusse',
            'PolicyName' => 'Access All Account Resources',
        ]));
    }

    public function testUpdateUser(): void
    {
        $client = $this->getClient();

        $input = new UpdateUserRequest([
            'UserName' => 'jderusse',
            'NewPath' => '/engineering/',
        ]);
        $client->UpdateUser($input);

        self::assertSame('/engineering/', $client->getUser(['UserName' => 'jderusse'])->getUser()->getPath());
    }

    private function getClient(): IamClient
    {
        return new IamClient([
            'endpoint' => 'http://localhost:4572',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
