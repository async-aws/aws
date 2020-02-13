<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class XmlParser
{
    /**
     * @var ServiceDefinition
     */
    private $definition;

    public function parseXmlResponseRoot(ServiceDefinition $definition, Shape $shape): string
    {
        $this->definition = $definition;
        $properties = [];

        foreach ($shape['members'] as $memberName => $memberData) {
            if (\in_array(($memberData['location'] ?? null), ['header', 'headers'])) {
                continue;
            }

            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => $memberName,
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse('$data', $memberName, $memberData),
            ]);
        }

        return implode("\n", $properties);
    }

    private function parseXmlResponse(string $currentInput, ?string $memberName, array $memberData)
    {
        $shapeName = $memberData['shape'];
        $shape = $this->definition->getShape($shapeName);
        if (!empty($memberData['xmlAttribute'])) {
            list($ns, $name) = explode(':', $memberData['locationName'] . ':');
            if (empty($name)) {
                // No namespace
                $name = $ns;
                $input = $currentInput . '[' . var_export($name, true) . '][0] ?? null';
            } else {
                $input = $currentInput . '->attributes(' . var_export($ns, true) . ', true)[' . var_export($name, true) . '][0] ?? null';
            }
        } elseif (isset($memberData['locationName'])) {
            $input = $currentInput . '->' . $memberData['locationName'];
        } elseif ($shape instanceof ListShape && $shape['flattened']) {
            $input = $currentInput . '->' . ($shape->getMember()['locationName'] ?? $memberName);
        } elseif ($memberName) {
            $input = $currentInput . '->' . $memberName;
        } else {
            $input = $currentInput;
        }

        switch ($shape['type']) {
            case 'list':
                /** @var ListShape $shape */
                return $this->parseXmlResponseList($shape, $input);
            case 'structure':
                /** @var StructureShape $shape */
                return $this->parseXmlResponseStructure($shape, $input);
            case 'map':
                return $this->parseXmlResponseMap($shape, $input);
            case 'string':
            case 'long':
                return $this->parseXmlResponseString($shape, $input);
            case 'integer':
                return $this->parseXmlResponseInteger($shape, $input);
            case 'float':
            case 'double':
                return $this->parseXmlResponseFloat($shape, $input);
            case 'boolean':
                return $this->parseXmlResponseBool($shape, $input);
            case 'blob':
                return $this->parseXmlResponseBlob($shape, $input);
            case 'timestamp':
                return $this->parseXmlResponseTimestamp($shape, $input);
            default:
                throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape['type']));
        }
    }

    private function parseXmlResponseStructure(StructureShape $shape, string $input): string
    {
        $properties = [];
        foreach ($shape['members'] as $memberName => $memberData) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($memberName, true),
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse($input, $memberName, $memberData),
            ]);
        }

        return strtr('new CLASS_NAME([
            PROPERTIES
        ])', [
            'CLASS_NAME' => GeneratorHelper::safeClassName($shape->getName()),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseXmlResponseString(Shape $shape, string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseInteger(Shape $shape, string $input): string
    {
        return strtr('($v = INPUT) ? (int) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseFloat(Shape $shape, string $input): string
    {
        return strtr('($v = INPUT) ? (float) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBool(Shape $shape, string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v === \'true\' : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBlob(Shape $shape, string $input): string
    {
        return strtr('($v = INPUT) ? base64_decode((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseTimestamp(Shape $shape, string $input): string
    {
        if (isset($shape['timestampFormat']) && 'unixTimestamp' === $shape['timestampFormat']) {
            return strtr('($v = INPUT) ? \DateTimeImmutable::setTimestamp((string) $v) : null', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? new \DateTimeImmutable((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseList(ListShape $shape, string $input): string
    {
        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach (INPUT_PROPERTY as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'LIST_ACCESSOR' => $this->parseXmlResponse('$item', null, ['shape' => $shape->getMember()['shape']]),
            'INPUT' => $input,
            'INPUT_PROPERTY' => ($shape['flattened'] ?? false ? '$xml' : '$xml->' . ($shape->getMember()['locationName'] ?? 'member')),
        ]);
    }

    private function parseXmlResponseMap(Shape $shape, string $input): string
    {
        if (!isset($shape['key']['locationName'])) {
            throw new \RuntimeException('This is not implemented yet');
        }

        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
               $items[$item->MAP_KEY->__toString()] = MAP_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'MAP_KEY' => $shape['key']['locationName'],
            'MAP_ACCESSOR' => $this->parseXmlResponse('$item', null, $shape['value']),
            'INPUT' => $input,
        ]);
    }
}
