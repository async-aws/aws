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
        /** @var Parameter $parameter */
        foreach ($parameters as $parameter) {
            if ((null === $name = $parameter->getName()) || (null === $value = $parameter->getValue())) {
                continue;
            }
            $name = strtoupper(strtr(ltrim(substr($name, $prefixLen), '/'), '/', '_'));
            $secrets[$name] = $value;
        }

        return $secrets;
    }
}
