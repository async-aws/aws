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

    /**
     * A Result can be resolved many times. This variable contains the last resolve result.
     *
     * @var bool|NetworkException|null
     */
    private $resolveResult;

    public function __construct(Response $response, AbstractApi $awsClient, $request)
    {
        $this->response = $response;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        try {
            $this->response->resolve();
        } catch (\Exception $exception) {
            // hide error
        }
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
        $this->needRefresh = true;
    }

    /**
     * Wait until the state is success.
     * Stopped when the state become Failure or the defined timeout is reached.
     *
     * @param float $timeout Duration in seconds before aborting
     * @param float $delay   Duration in seconds between each check
     */
    final public function wait(float $timeout = null, float $delay = null): bool
    {
        $timeout = $timeout ?? static::WAIT_TIMEOUT;
        $delay = $delay ?? static::WAIT_DELAY;

        $start = \microtime(true);
        while (true) {
            try {
                if (!$this->response->resolve($timeout - (\microtime(true) - $start))) {
                    break;
                }
            } catch (HttpException $exception) {
                // HTTP errors are valid responses
            }

            switch ($this->getState()) {
                case self::STATE_SUCCESS:
                case self::STATE_FAILURE:
                    return true;
            }

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
