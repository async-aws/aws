<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\ListShardsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListShardsOutputTest extends TestCase
{
    public function testListShardsOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListShards.html
        $response = new SimpleMockedResponse('{
    "NextToken": "AAAAAAAAAAGK9EEG0sJqVhCUS2JsgigQ5dcpB4q9PYswrH2oK44Skbjtm+WR0xA7/hrAFFsohevH1/OyPnbzKBS1byPyCZuVcokYtQe/b1m4c0SCI7jctPT0oUTLRdwSRirKm9dp9YC/EL+kZHOvYAUnztVGsOAPEFC3ECf/bVC927bDZBbRRzy/44OHfWmrCLcbcWqehRh5D14WnL3yLsumhiHDkyuxSlkBepauvMnNLtTOlRtmQ5Q5reoujfq2gzeCSOtLcfXgBMztJqohPdgMzjTQSbwB9Am8rMpHLsDbSdMNXmITvw==",
    "Shards": [
        {
            "ShardId": "shardId-000000000001",
            "HashKeyRange": {
                "EndingHashKey": "68056473384187692692674921486353642280",
                "StartingHashKey": "34028236692093846346337460743176821145"
            },
            "SequenceNumberRange": {
                "StartingSequenceNumber": "49579844037727333356165064238440708846556371693205002258"
            }
        },
        {
            "ShardId": "shardId-000000000002",
            "HashKeyRange": {
                "EndingHashKey": "102084710076281539039012382229530463436",
                "StartingHashKey": "68056473384187692692674921486353642281"
            },
            "SequenceNumberRange": {
                "StartingSequenceNumber": "49579844037749634101363594861582244564829020124710982690"
            }
        },
        {
            "ShardId": "shardId-000000000003",
            "HashKeyRange": {
                "EndingHashKey": "136112946768375385385349842972707284581",
                "StartingHashKey": "102084710076281539039012382229530463437"
            },
            "SequenceNumberRange": {
                "StartingSequenceNumber": "49579844037771934846562125484723780283101668556216963122"
            }
        }
    ]
}');

        $client = new MockHttpClient($response);
        $result = new ListShardsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(3, $result->getShards());
        self::assertSame('shardId-000000000001', $result->getShards()[0]->getShardId());
    }
}
