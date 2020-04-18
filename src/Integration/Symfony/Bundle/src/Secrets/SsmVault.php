<?php

namespace AsyncAws\Symfony\Bundle\Secrets;

use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Parameter;
use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;

class SsmVault implements EnvVarLoaderInterface
{
    private $client;
    private $path;
    private $recursive;

    public function __construct(SsmClient $client, ?string $path, bool $recursive)
    {
        $this->client = $client;
        $this->path = $path ?? '/';
        $this->recursive = $recursive;
    }

    public function loadEnvVars(): array
    {
        $parameters = $this->client->getParametersByPath([
            'Path' => $this->path,
            'Recursive' => $this->recursive,
            'WithDecryption' => true,
        ]);

        $secrets = [];
        $prefixLen = \strlen($this->path);
        /** @var Parameter[] $parameters */
        foreach ($parameters as $parameter) {
            $name = \strtoupper(\strtr(ltrim(substr($parameter->getName(), $prefixLen), '/'), '/', '_'));
            $secrets[$name] = $parameter->getValue();
        }

        return $secrets;
    }
}
