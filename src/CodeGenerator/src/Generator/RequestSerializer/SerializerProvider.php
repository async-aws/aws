<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Provide the correct serializer according to the definition.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class SerializerProvider
{
    private $namespaceRegistry;

    private $requirementsRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
    }

    public function get(ServiceDefinition $definition): Serializer
    {
        switch ($definition->getProtocol()) {
            case 'rest-xml':
                return new RestXmlSerializer($this->namespaceRegistry, $this->requirementsRegistry);
            case 'rest-json':
                return new RestJsonSerializer($this->namespaceRegistry, $this->requirementsRegistry);
            case 'query':
                return new QuerySerializer($this->namespaceRegistry);
            case 'json':
                return new JsonRpcSerializer($this->namespaceRegistry, $this->requirementsRegistry);

            default:
                throw new \LogicException(sprintf('Serializer for "%s" is not implemented yet', $definition->getProtocol()));
        }
    }
}
