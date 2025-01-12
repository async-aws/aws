<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Result\CreateTokenResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateTokenResponseTest extends TestCase
{
    public function testCreateTokenResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "accessToken": "aoal-YigITUDiNX1xZwOMXM5MxOWDL0E0jg9P6_C_jKQPxS_SKCP6f0kh1Up4g7TtvQqkMnD-GJiU_S1gvug6SrggAkc0:MGYCMQD3IatVjV7jAJU91kK3PkS\\/SfA2wtgWzOgZWDOR7sDGN9t0phCZz5It\\/aes\\/3C1Zj0CMQCKWOgRaiz6AIhza3DSXQNMLjRKXC8F8ceCsHlgYLMZ7hZidEXAMPLEACCESSTOKEN",
            "expiresIn": 1579729529,
            "refreshToken": "aorvJYubGpU6i91YnH7Mfo-AT2fIVa1zCfA_Rvq9yjVKIP3onFmmykuQ7E93y2I-9Nyj-A_sVvMufaLNL0bqnDRtgAkc0:MGUCMFrRsktMRVlWaOR70XGMFGLL0SlcCw4DiYveIiOVx1uK9BbD0gvAddsW3UTLozXKMgIxAJ3qxUvjpnlLIOaaKOoa\\/FuNgqJVvr9GMwDtnAtlh9iZzAkEXAMPLEREFRESHTOKEN",
            "tokenType": "Bearer"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateTokenResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('aoal-YigITUDiNX1xZwOMXM5MxOWDL0E0jg9P6_C_jKQPxS_SKCP6f0kh1Up4g7TtvQqkMnD-GJiU_S1gvug6SrggAkc0:MGYCMQD3IatVjV7jAJU91kK3PkS/SfA2wtgWzOgZWDOR7sDGN9t0phCZz5It/aes/3C1Zj0CMQCKWOgRaiz6AIhza3DSXQNMLjRKXC8F8ceCsHlgYLMZ7hZidEXAMPLEACCESSTOKEN', $result->getAccessToken());
        self::assertSame('Bearer', $result->getTokenType());
        self::assertSame(1579729529, $result->getExpiresIn());
        self::assertSame('aorvJYubGpU6i91YnH7Mfo-AT2fIVa1zCfA_Rvq9yjVKIP3onFmmykuQ7E93y2I-9Nyj-A_sVvMufaLNL0bqnDRtgAkc0:MGUCMFrRsktMRVlWaOR70XGMFGLL0SlcCw4DiYveIiOVx1uK9BbD0gvAddsW3UTLozXKMgIxAJ3qxUvjpnlLIOaaKOoa/FuNgqJVvr9GMwDtnAtlh9iZzAkEXAMPLEREFRESHTOKEN', $result->getRefreshToken());
    }
}
