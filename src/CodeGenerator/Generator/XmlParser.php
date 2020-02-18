<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class XmlParser
{
    public function parseXmlResponseRoot(StructureShape $shape): string
    {
        $properties = [];

        foreach ($shape->getMembers() as $member) {
            if (\in_array($member->getLocation(), ['header', 'headers'])) {
                continue;
            }

            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => $member->getName(),
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse($this->getInputAccessor('$data', $member), $member->getShape()),
            ]);
        }

        return implode("\n", $properties);
    }

    private function getInputAccessor(string $currentInput, Member $member)
    {
        if ($member instanceof StructureMember) {
            if ($member->isXmlAttribute()) {
                [$ns, $name] = explode(':', $member->getLocationName() . ':');
                if (empty($name)) {
                    return $currentInput . '[' . var_export($ns, true) . '][0] ?? null';
                }

                return $currentInput . '->attributes(' . var_export($ns, true) . ', true)[' . var_export($name, true) . '][0] ?? null';
            }

            $shape = $member->getShape();
            if ($shape instanceof ListShape && $shape->isFlattened()) {
                return $currentInput . '->' . ($member->getLocationName() ?? $shape->getMember()->getLocationName() ?? $member->getName());
            }

            return $currentInput . '->' . ($member->getLocationName() ?? $member->getName());
        }

        return $currentInput . ($member->getLocationName() ? '->' . $member->getLocationName() : '');
    }

    private function parseXmlResponse(string $input, Shape $shape)
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseXmlResponseList($shape, $input);
            case $shape instanceof StructureShape:
                return $this->parseXmlResponseStructure($shape, $input);
            case $shape instanceof MapShape:
                return $this->parseXmlResponseMap($shape, $input);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'long':
                return $this->parseXmlResponseString($input);
            case 'integer':
                return $this->parseXmlResponseInteger($input);
            case 'float':
            case 'double':
                return $this->parseXmlResponseFloat($input);
            case 'boolean':
                return $this->parseXmlResponseBool($input);
            case 'blob':
                return $this->parseXmlResponseBlob($input);
            case 'timestamp':
                return $this->parseXmlResponseTimestamp($shape, $input);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseXmlResponseStructure(StructureShape $shape, string $input): string
    {
        $properties = [];
        foreach ($shape->getMembers() as $member) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($member->getName(), true),
                'PROPERTY_ACCESSOR' => $this->parseXmlResponse($this->getInputAccessor($input, $member), $member->getShape()),
            ]);
        }

        return strtr('new CLASS_NAME([
            PROPERTIES
        ])', [
            'CLASS_NAME' => GeneratorHelper::safeClassName($shape->getName()),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseXmlResponseString(string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseInteger(string $input): string
    {
        return strtr('($v = INPUT) ? (int) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseFloat(string $input): string
    {
        return strtr('($v = INPUT) ? (float) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBool(string $input): string
    {
        return strtr('($v = INPUT) ? (string) $v === \'true\' : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBlob(string $input): string
    {
        return strtr('($v = INPUT) ? base64_decode((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseTimestamp(Shape $shape, string $input): string
    {
        if ('unixTimestamp' === $shape->get('timestampFormat')) {
            return strtr('($v = INPUT) ? \DateTimeImmutable::setTimestamp((string) $v) : null', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? new \DateTimeImmutable((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseList(ListShape $shape, string $input): string
    {
        $shapeMember = $shape->getMember();

        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach (INPUT_PROPERTY as $item) {
               $items[] = LIST_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'LIST_ACCESSOR' => $this->parseXmlResponse('$item', $shapeMember->getShape()),
            'INPUT' => $input,
            'INPUT_PROPERTY' => $shape->isFlattened() ? '$xml' : '$xml' . ($shapeMember->getLocationName() ? '->' . $shapeMember->getLocationName() : ''),
        ]);
    }

    private function parseXmlResponseMap(MapShape $shape, string $input): string
    {
        if (null === $locationName = $shape->getKey()->getLocationName()) {
            throw new \RuntimeException('This is not implemented yet');
        }

        $shapeValue = $shape->getValue();

        return strtr('(function(\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
               $items[$item->MAP_KEY->__toString()] = MAP_ACCESSOR;
            }

            return $items;
        })(INPUT)', [
            'MAP_KEY' => $locationName,
            'MAP_ACCESSOR' => $this->parseXmlResponse($this->getInputAccessor('$item', $shapeValue), $shapeValue->getShape()),
            'INPUT' => $input,
        ]);
    }
}
