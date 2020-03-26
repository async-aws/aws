<?php
declare(strict_types=1);

namespace AsyncAws\Lambda\Closure;

use AsyncAws\Lambda\Result\InvocationResponse;

/**
 * Ease invokeBatchAsync implementation
 * Allow unit testing of the callable implementation
 *
 * Example:
 *
 * // Instead of untestable Business Logic
 * $callback = function(InvocationResponse $invocationResponse) {
 *     // Business Logic
 * };
 * $callback($invocationResponse);
 *
 * // Testable Business Logic
 * $callback = new BatchAsyncClosure(); // Implements BatchAsyncClosureInterface
 * $callback($invocationResponse);
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
interface BatchAsyncClosureInterface
{
    public function __invoke(InvocationResponse $invocationResponse): void;
}
