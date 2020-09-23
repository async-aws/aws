<?php

namespace AsyncAws\Iam\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Result\ListUsersResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListUsersResponseTest extends TestCase
{
    public function testListUsersResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<ListUsersResponse xmlns="https://iam.amazonaws.com/doc/2010-05-08/">
         <ListUsersResult>
            <Users>
               <member>
                <Arn>arn:aws:iam::123456789012:user/division_abc/subdivision_xyz/engineering/Juan</Arn>
                <CreateDate>2012-09-05T19:38:48Z</CreateDate>
                <PasswordLastUsed>2016-09-08T21:47:36Z</PasswordLastUsed>
                <Path>/division_abc/subdivision_xyz/engineering/</Path>
                <UserId>AID2MAB8DPLSRHEXAMPLE</UserId>
                <UserName>Juan</UserName>
               </member>
               <member>
                <Arn>arn:aws:iam::123456789012:user/division_abc/subdivision_xyz/engineering/Anika</Arn>
                <CreateDate>2014-04-09T15:43:45Z</CreateDate>
                <PasswordLastUsed>2016-09-24T16:18:07Z</PasswordLastUsed>
                <Path>/division_abc/subdivision_xyz/engineering/</Path>
                <UserId>AIDIODR4TAW7CSEXAMPLE</UserId>
                <UserName>Anika</UserName>
               </member>
            </Users>
            <IsTruncated>false</IsTruncated>
         </ListUsersResult>
         <ResponseMetadata>
            <RequestId>7a62c49f-347e-4fc4-9331-6e8eEXAMPLE</RequestId>
         </ResponseMetadata>
        </ListUsersResponse>');

        $client = new MockHttpClient($response);
        $result = new ListUsersResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IamClient(), new ListUsersRequest());

        self::assertFalse($result->getIsTruncated());
        self::assertCount(2, $result->getUsers(true));
        self::assertSame('Juan', \iterator_to_array($result->getUsers())[0]->getUserName());
        self::assertSame('Anika', \iterator_to_array($result->getUsers())[1]->getUserName());
    }
}
