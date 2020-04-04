<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;

/**
 * The waiter promise is always returned from every API call to a waiter.
 */
class Waiter
{
    public const STATE_SUCCESS = 'success';
    public const STATE_FAILURE = 'failure';
    public const STATE_PENDING = 'pending';

    protected const WAIT_TIMEOUT = 30.0;
    protected const WAIT_DELAY = 5.0;

    /**
     * @var AbstractApi|null
     */
    protected $awsClient;

    /**
     * Input used to build the API request that generate this Waiter.
     *
     * @var object|null
     */
    protected $input;

    /**
     * @var Response
     */
    private $response;

    /**
     * Whether or not a new response should be fetched.
     *
     * @var bool
     */
    private $needRefresh = false;

    /**
     * @var string|null
     */
    private $finalState;

    public function __construct(Response $response, AbstractApi $awsClient, $request)
    {
        $this->response = $response;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        $this->resolve();
    }

    final public function isSuccess(): bool
    {
        return self::STATE_SUCCESS === $this->getState();
    }

    final public function isFailure(): bool
    {
        return self::STATE_FAILURE === $this->getState();
    }

    final public function isPending(): bool
    {
        return self::STATE_PENDING === $this->getState();
    }

    final public function getState(): string
    {
        if (null !== $this->finalState) {
            return $this->finalState;
        }

        if ($this->needRefresh) {
            $this->stealResponse($this->refreshState());
        }

        try {
            $this->response->resolve();
            $exception = null;
        } catch (HttpException $exception) {
            // use $exception later
        }

        $state = $this->extractState($this->response, $exception);
        $this->needRefresh = true;

        switch ($state) {
            case self::STATE_SUCCESS:
            case self::STATE_FAILURE:
                $this->finalState = $state;

                break;
            case self::STATE_PENDING:
                break;
            default:
                throw new \LogicException(sprintf('Unexpected state "%s" from Waiter "%s".', $state, __CLASS__));
        }

        return $state;
    }

    /**
     * Make sure the actual request is executed.
     *
     * @param float|null $timeout Duration in seconds before aborting. When null wait until the end of execution.
     *
     * @return bool false on timeout. True if the response has returned with as status code.
     *
     * @throws NetworkException
     */
    final public function resolve(?float $timeout = null): bool
    {
        try {
            return $this->response->resolve($timeout);
        } catch (HttpException $exception) {
            return true;
        }
    }

    /**
     * Returns info on the current request.
     *
     * @return array{
     *                resolved: bool,
     *                body_downloaded: bool,
     *                response: \Symfony\Contracts\HttpClient\ResponseInterface,
     *                status: int,
     *                }
     */
    final public function info(): array
    {
        return $this->response->info();
    }

    final public function cancel(): void
    {
        $this->response->cancel();
        $this->needRefresh = true;
    }

    /**
     * Wait until the state is success.
     * Stopped when the state become Failure or the defined timeout is reached.
     *
     * @param float $timeout Duration in seconds before aborting
     * @param float $delay   Duration in seconds between each check
     *
     * @return bool true if a final state was reached
     */
    final public function wait(float $timeout = null, float $delay = null): bool
    {
        $timeout = $timeout ?? static::WAIT_TIMEOUT;
        $delay = $delay ?? static::WAIT_DELAY;

        $start = \microtime(true);
        while (true) {
            // If request times out
            if (!$this->resolve($timeout - (\microtime(true) - $start))) {
                break;
            }

            // If we reached a final state
            if (\in_array($this->getState(), [self::STATE_SUCCESS, self::STATE_FAILURE])) {
                return true;
            }

            // If the timeout will expire during our sleep, then exit early.
            if ($delay > $timeout - (\microtime(true) - $start)) {
                break;
            }

            \usleep((int) ceil($delay * 1000000));
            $this->stealResponse($this->refreshState());
        }

        return false;
    }

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        return self::STATE_PENDING;
    }

    protected function refreshState(): Waiter
    {
        return $this;
    }

    private function stealResponse(self $waiter): void
    {
        $this->response = $waiter->response;
        $this->needRefresh = false;
    }
}
