<?php

namespace AsyncAws\Ecr\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ecr\ValueObject\AuthorizationData;

class GetAuthorizationTokenResponse extends Result
{
    /**
     * A list of authorization token data objects that correspond to the `registryIds` values in the request.
     *
     * > The size of the authorization token returned by Amazon ECR is not fixed. We recommend that you don't make
     * > assumptions about the maximum size.
     *
     * @var AuthorizationData[]
     */
    private $authorizationData;

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

    private function populateResultAuthorizationData(array $json): AuthorizationData
    {
        return new AuthorizationData([
            'authorizationToken' => isset($json['authorizationToken']) ? (string) $json['authorizationToken'] : null,
            'expiresAt' => (isset($json['expiresAt']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['expiresAt'])))) ? $d : null,
            'proxyEndpoint' => isset($json['proxyEndpoint']) ? (string) $json['proxyEndpoint'] : null,
        ]);
    }

    /**
     * @return AuthorizationData[]
     */
    private function populateResultAuthorizationDataList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAuthorizationData($item);
        }

        return $items;
    }
}
