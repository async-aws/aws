<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class RestXmlParser implements Parser
{
    private $namespaceRegistry;

    private $typeGenerator;

    private $functions = [];

    private $imports = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, TypeGenerator $typeGenerator)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->typeGenerator = $typeGenerator;
    }

    public function generate(StructureShape $shape, bool $throwOnError = true): ParserResult
    {
        $properties = [];
        $this->functions = [];
        $this->imports = [];
        if (null !== $payload = $shape->getPayload()) {
            $member = $shape->getMember($payload);
            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                'PROPERTY_ACCESSOR' => $this->parseXmlElement('$data', $member->getShape(), $member->isRequired() || null === $shape->getResultWrapper()),
            ]);
        } else {
            foreach ($shape->getMembers() as $member) {
                if (\in_array($member->getLocation(), ['header', 'headers'])) {
                    continue;
                }

                if (!$member->isNullable() && !$member->isRequired()) {
                    $properties[] = strtr('if (null !== $v = (PROPERTY_ACCESSOR)) {
                        $this->PROPERTY_NAME = $v;
                    }', [
                        'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                        'PROPERTY_ACCESSOR' => $this->parseXmlElement($this->getInputAccessor('$data', $member), $member->getShape(), $member->isRequired()),
                    ]);
                } else {
                    $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                        'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                        'PROPERTY_ACCESSOR' => $this->parseXmlElement($this->getInputAccessor('$data', $member), $member->getShape(), $member->isRequired()),
                    ]);
                }
            }
        }

        if (empty($properties)) {
            return new ParserResult('');
        }

        $body = '$data = new \SimpleXMLElement($response->getContent(' . ($throwOnError ? '' : 'false') . '));';
        if (!$throwOnError) {
            $body .= 'if (0 < $data->Error->count()) {
                $data = $data->Error;
            }';
        }
        if (null !== $wrapper = $shape->getResultWrapper()) {
            $body .= strtr('$data = $data->WRAPPER;' . "\n", ['WRAPPER' => $wrapper]);
        }
        $body .= "\n" . implode("\n", $properties);

        return new ParserResult($body, $this->imports, $this->functions);
    }

    private function getInputAccessor(string $currentInput, Member $member)
    {
        if ($member instanceof StructureMember) {
            if ($member->isXmlAttribute()) {
                [$ns, $name] = explode(':', $member->getLocationName() . ':');
                $replaceData = [
                    'INPUT' => $currentInput,
                    'NS' => var_export($ns, true),
                    'OR_NULL' => $member->isRequired() ? ' ?? null' : '',
                ];

                if (empty($name)) {
                    return strtr('(INPUT[NS][0]OR_NULL)', $replaceData);
                }

                $replaceData['NAME'] = var_export($name, true);

                return strtr('(INPUT->attributes(NS, true)[NAME][0]OR_NULL)', $replaceData);
            }

            $shape = $member->getShape();
            if ($shape instanceof ListShape && $shape->isFlattened()) {
                return $currentInput . '->' . ($member->getLocationName() ?? $shape->getMember()->getLocationName() ?? $member->getName());
            }

            return $currentInput . '->' . ($member->getLocationName() ?? $member->getName());
        }

        return $currentInput . ($member->getLocationName() ? '->' . $member->getLocationName() : '');
    }

    private function parseXmlElement(string $input, Shape $shape, bool $required)
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseXmlResponseList($shape, $input, $required);
            case $shape instanceof StructureShape:
                return $this->parseXmlResponseStructure($shape, $input, $required);
            case $shape instanceof MapShape:
                return $this->parseXmlResponseMap($shape, $input, $required);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'long':
                return $this->parseXmlResponseString($input, $required);
            case 'integer':
                return $this->parseXmlResponseInteger($input, $required);
            case 'float':
            case 'double':
                return $this->parseXmlResponseFloat($input, $required);
            case 'boolean':
                return $this->parseXmlResponseBool($input, $required);
            case 'blob':
                return $this->parseXmlResponseBlob($input, $required);
            case 'timestamp':
                return $this->parseXmlResponseTimestamp($shape, $input, $required);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseXmlResponseStructure(StructureShape $shape, string $input, bool $required): string
    {
        $properties = [];
        foreach ($shape->getMembers() as $member) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($member->getName(), true),
                'PROPERTY_ACCESSOR' => $this->parseXmlElement($this->getInputAccessor($input, $member), $member->getShape(), $member->isRequired()),
            ]);
        }

        return strtr('REQUIRED new CLASS_NAME([
            PROPERTIES
        ])', [
            'REQUIRED' => $required ? '' : '!' . $input . ' ? null : ',
            'CLASS_NAME' => $this->namespaceRegistry->getObject($shape)->getName(),
            'PROPERTIES' => implode("\n", $properties),
        ]);
    }

    private function parseXmlResponseString(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(string) INPUT', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseInteger(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(int) (string) INPUT', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? (int) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseFloat(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(float) (string) INPUT', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? (float) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBool(string $input, bool $required): string
    {
        if ($required) {
            return strtr('filter_var((string) INPUT, FILTER_VALIDATE_BOOLEAN)', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? filter_var((string) $v, FILTER_VALIDATE_BOOLEAN) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBlob(string $input, bool $required): string
    {
        if ($required) {
            return strtr('base64_decode((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? base64_decode((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        if ('unixTimestamp' === $shape->get('timestampFormat')) {
            if ($required) {
                return strtr('\DateTimeImmutable::setTimestamp((string) INPUT)', ['INPUT' => $input]);
            }

            return strtr('($v = INPUT) ? \DateTimeImmutable::setTimestamp((string) $v) : null', ['INPUT' => $input]);
        }

        if ($required) {
            return strtr('new \DateTimeImmutable((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('($v = INPUT) ? new \DateTimeImmutable((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseList(ListShape $shape, string $input, bool $required): string
    {
        $shapeMember = $shape->getMember();
        if ($shapeMember->getShape() instanceof StructureShape) {
            $listAccessorRequired = true;
            $body = '
                $items = [];
                foreach (INPUT_PROPERTY as $item) {
                   $items[] = LIST_ACCESSOR;
                }

                return $items;
            ';
        } else {
            $listAccessorRequired = false;
            $body = '
                $items = [];
                foreach (INPUT_PROPERTY as $item) {
                    $a = LIST_ACCESSOR;
                    if (null !== $a) {
                        $items[] = $a;
                    }
                }

                return $items;
            ';
        }

        $functionName = 'populateResult' . ucfirst($shape->getName());
        $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
            'LIST_ACCESSOR' => $this->parseXmlElement('$item', $shapeMember->getShape(), $listAccessorRequired),
            'INPUT_PROPERTY' => $shape->isFlattened() ? '$xml' : ('$xml->' . ($shapeMember->getLocationName() ? $shapeMember->getLocationName() : 'member')),
        ]), $shape);

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : '!INPUT ? [] : $this->FUNCTION_NAME(INPUT)', [
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function parseXmlResponseMap(MapShape $shape, string $input, bool $required): string
    {
        if (null === $locationName = $shape->getKey()->getLocationName()) {
            throw new \RuntimeException('This is not implemented yet');
        }

        $shapeValue = $shape->getValue();
        $body = '
            $items = [];
            foreach ($xml as $item) {
                if (null === $a = VALUE) {
                    continue;
                }
                $items[$item->MAP_KEY->__toString()] = MAP_ACCESSOR;
            }

            return $items;
        ';

        $functionName = 'populateResult' . ucfirst($shape->getName());
        $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
            'MAP_KEY' => $locationName,
            'VALUE' => $this->getInputAccessor('$item', $shapeValue),
            'MAP_ACCESSOR' => $this->parseXmlElement('$a', $shapeValue->getShape(), true),
        ]), $shape);

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : '!INPUT ? [] : $this->FUNCTION_NAME(INPUT)', [
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function createPopulateMethod(string $functionName, string $body, Shape $shape): Method
    {
        $method = new Method($functionName);
        $method->setVisibility(ClassType::VISIBILITY_PRIVATE)
            ->setReturnType('array')
            ->setBody($body)
            ->addParameter('xml')
                ->setType(\SimpleXMLElement::class)
        ;

        if (null !== $shape) {
            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($shape);
            $method
                ->setComment('@return ' . $parameterType);
            $this->imports = array_merge($this->imports, $memberClassNames);
        }

        return $method;
    }
}
