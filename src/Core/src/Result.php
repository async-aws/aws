<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Base class for all return values from a Api Client methods. Example: `FooClient::bar(): Result`.
 */
class Result
{
    /**
     * @var AbstractApi|null
     */
    protected $awsClient;

    /**
     * Input used to build the API request that generate this Result.
     *
     * @var object|null
     */
    protected $input;

    protected $initialized = false;

    private $response;

    /**
     * @var self[]
     */
    private $prefetchResults = [];

    public function __construct(Response $response, AbstractApi $awsClient = null, $request = null)
    {
        $this->response = $response;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        while (!empty($this->prefetchResponses)) {
            array_shift($this->prefetchResponses)->cancel();
        }
    }

    /**
     * Make sure the actual request is executed.
     *
     * @param float|null $timeout Duration in seconds before aborting. When null wait until the end of execution.
     *
     * @return bool whether the request is executed or not
     *
     * @throws NetworkException
     * @throws HttpException
     */
    final public function resolve(?float $timeout = null): bool
    {
        return $this->response->resolve($timeout);
    }

    /**
     * Returns info on the current request.
     *
     * @return array{
     *                resolved: bool,
     *                response?: ?\Symfony\Contracts\HttpClient\ResponseInterface,
     *                status?: int
     *                }
     */
    final public function info(): array
    {
        return $this->response->info();
    }

    final public function cancel(): void
    {
        $this->response->cancel();
    }

    /**
     * This only work if the http responses are produced by the same HTTP client.
     * See https://symfony.com/doc/current/components/http_client.html#multiplexing-responses.
     *
     * @param self[] $results
     */
    public static function multiplex(array $results, float $timeout = null): ResponseStreamInterface
    {
        $responses = [];
        foreach ($results as $result) {
            $responses[] = $result->response;
        }

        return Response::multiplex($responses, $timeout);
    }

    final protected function registerPrefetch(self $result): void
    {
        $this->prefetchResults[spl_object_id($result)] = $result;
    }

    final protected function unregisterPrefetch(self $result): void
    {
        unset($this->prefetchResults[spl_object_id($result)]);
    }

    final protected function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        $this->resolve();
        $this->initialized = true;
        $this->populateResult($this->response);
    }

    protected function populateResult(Response $response): void
    {
    }
}
