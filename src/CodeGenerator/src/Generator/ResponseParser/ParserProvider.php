<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Provide the correct parser according to the definition.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class ParserProvider
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function get(ServiceDefinition $definition): Parser
    {
        switch ($definition->getProtocol()) {
            case 'query':
            case 'rest-xml':
                return new RestXmlParser($this->namespaceRegistry);
            case 'rest-json':
                return new RestJsonParser($this->namespaceRegistry);
            case 'json':
                return new JsonRpcParser($this->namespaceRegistry);
            default:
                throw new \LogicException(sprintf('Parser for "%s" is not implemented yet', $definition->getProtocol()));
        }
    }
}
