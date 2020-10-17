<?php

namespace AsyncAws\Ecr\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ecr\ValueObject\AuthorizationData;

class GetAuthorizationTokenResponse extends Result
{
    /**
     * A list of authorization token data objects that correspond to the `registryIds` values in the request.
     */
    private $authorizationData = [];

    /**
     * @return AuthorizationData[]
     */
    public function getAuthorizationData(): array
    {
        $this->initialize();

        return $this->authorizationData;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->authorizationData = empty($data['authorizationData']) ? [] : $this->populateResultAuthorizationDataList($data['authorizationData']);
    }

    /**
     * @return AuthorizationData[]
     */
    private function populateResultAuthorizationDataList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new AuthorizationData([
                'authorizationToken' => isset($item['authorizationToken']) ? (string) $item['authorizationToken'] : null,
                'expiresAt' => (isset($item['expiresAt']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['expiresAt'])))) ? $d : null,
                'proxyEndpoint' => isset($item['proxyEndpoint']) ? (string) $item['proxyEndpoint'] : null,
            ]);
        }

        return $items;
    }
}
