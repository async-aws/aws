<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\ExpirationStatus;
use AsyncAws\S3\Enum\TransitionStorageClass;
use AsyncAws\S3\Result\GetBucketLifecycleConfigurationOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBucketLifecycleConfigurationOutputTest extends TestCase
{
    public function testGetBucketLifecycleConfigurationOutput(): void
    {
        $response = new SimpleMockedResponse('
<LifecycleConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <Rule>
        <ID>Archive and then delete rule</ID>
        <Filter>
            <Prefix>projectdocs/</Prefix>
        </Filter>
        <Status>Enabled</Status>
        <Transition>
            <Days>30</Days>
            <StorageClass>STANDARD_IA</StorageClass>
        </Transition>
        <Transition>
            <Days>365</Days>
            <StorageClass>GLACIER</StorageClass>
        </Transition>
        <Expiration>
            <Days>3650</Days>
        </Expiration>
    </Rule>
</LifecycleConfiguration>');

        $client = new MockHttpClient($response);
        $result = new GetBucketLifecycleConfigurationOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $rules = $result->getRules();
        self::assertCount(1, $rules);

        $rule = $rules[0];
        self::assertSame('Archive and then delete rule', $rule->getId());
        self::assertSame(ExpirationStatus::ENABLED, $rule->getStatus());
        self::assertSame('projectdocs/', $rule->getFilter()->getPrefix());

        $transitions = $rule->getTransitions();
        self::assertCount(2, $transitions);
        self::assertSame(30, $transitions[0]->getDays());
        self::assertSame(TransitionStorageClass::STANDARD_IA, $transitions[0]->getStorageClass());
        self::assertSame(365, $transitions[1]->getDays());
        self::assertSame(TransitionStorageClass::GLACIER, $transitions[1]->getStorageClass());

        $expiration = $rule->getExpiration();
        self::assertNotNull($expiration);
        self::assertSame(3650, $expiration->getDays());
    }
}
