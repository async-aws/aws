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
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class RestJsonParser implements Parser
{
    private $namespaceRegistry;

    private $requirementsRegistry;

    private $typeGenerator;

    private $functions = [];

    private $imports = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, TypeGenerator $typeGenerator)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
        $this->typeGenerator = $typeGenerator;
    }

    public function generate(StructureShape $shape, bool $throwOnError = true): ParserResult
    {
        if (null !== $payloadProperty = $shape->getPayload()) {
            return new ParserResult(strtr('$this->PROPERTY_NAME = $response->getContent(THROW);', ['THROW' => $throwOnError ? '' : 'false', 'PROPERTY_NAME' => GeneratorHelper::normalizeName($payloadProperty)]));
        }

        $properties = [];
        $this->functions = [];
        $this->imports = [];
        foreach ($shape->getMembers() as $member) {
            if (\in_array($member->getLocation(), ['header', 'headers'])) {
                continue;
            }

            if (!$member->isNullable() && !$member->isRequired()) {
                $properties[] = strtr('if (null !== $v = (PROPERTY_ACCESSOR)) {
                        $this->PROPERTY_NAME = $v;
                    }', [
                    'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                    'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired()),
                ]);
            } else {
                $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                    'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                    'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired()),
                ]);
            }
        }

        if (empty($properties)) {
            return new ParserResult('');
        }

        $body = '$data = $response->toArray(' . ($throwOnError ? '' : 'false') . ');' . "\n";
        if (null !== $wrapper = $shape->getResultWrapper()) {
            $body .= strtr('$data = $data[WRAPPER];' . "\n", ['WRAPPER' => var_export($wrapper, true)]);
        }
        $body .= "\n" . implode("\n", $properties);

        return new ParserResult($body, $this->imports, $this->functions);
    }

    protected function parseResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        $body = 'new \DateTimeImmutable((string) INPUT)';

        if (!$required) {
            $body = 'isset(INPUT) ? ' . $body . ' : null';
        }

        return strtr($body, ['INPUT' => $input]);
    }

    private function getInputAccessorName(Member $member)
    {
        if ($member instanceof StructureMember) {
            $shape = $member->getShape();
            if ($shape instanceof ListShape && $shape->isFlattened()) {
                return $member->getLocationName() ?? $shape->getMember()->getLocationName() ?? $member->getName();
            }

            return $member->getLocationName() ?? $member->getName();
        }

        if (null === $member->getLocationName()) {
            throw new \RuntimeException('This should not happen');
        }

        return $member->getLocationName();
    }

    private function parseElement(string $input, Shape $shape, bool $required)
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseResponseList($shape, $input, $required);
            case $shape instanceof StructureShape:
                return $this->parseResponseStructure($shape, $input, $required);
            case $shape instanceof MapShape:
                return $this->parseResponseMap($shape, $input, $required);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'long':
                return $this->parseResponseString($input, $required);
            case 'integer':
                return $this->parseResponseInteger($input, $required);
            case 'float':
            case 'double':
                return $this->parseResponseFloat($input, $required);
            case 'boolean':
                return $this->parseResponseBool($input, $required);
            case 'blob':
                return $this->parseResponseBlob($input, $required);
            case 'timestamp':
                return $this->parseResponseTimestamp($shape, $input, $required);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseResponseStructure(StructureShape $shape, string $input, bool $required): string
    {
        $properties = [];
        foreach ($shape->getMembers() as $member) {
            $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                'PROPERTY_NAME' => var_export($member->getName(), true),
                'PROPERTY_ACCESSOR' => $this->parseElement(sprintf('%s[\'%s\']', $input, $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired()),
            ]);
        }

        $body = 'new CLASS_NAME([
            PROPERTIES
        ])';

        if (!$required) {
            $body = 'empty(INPUT) ? null : ' . $body;
        }

        $className = $this->namespaceRegistry->getObject($shape);
        $this->imports[] = $className;

        return strtr(
            $body, [
                'INPUT' => $input,
                'CLASS_NAME' => $className->getName(),
                'PROPERTIES' => implode("\n", $properties),
            ]);
    }

    private function parseResponseString(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(string) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (string) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseInteger(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(int) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (int) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseFloat(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(float) INPUT', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? (float) INPUT : null', ['INPUT' => $input]);
    }

    private function parseResponseBool(string $input, bool $required): string
    {
        $this->requirementsRegistry->addRequirement('ext-filter');
        if ($required) {
            return strtr('filter_var(INPUT, FILTER_VALIDATE_BOOLEAN)', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? filter_var(INPUT, FILTER_VALIDATE_BOOLEAN) : null', ['INPUT' => $input]);
    }

    private function parseResponseBlob(string $input, bool $required): string
    {
        if ($required) {
            return strtr('base64_decode((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('isset(INPUT) ? base64_decode((string) INPUT) : null', ['INPUT' => $input]);
    }

    private function parseResponseList(ListShape $shape, string $input, bool $required): string
    {
        $shapeMember = $shape->getMember();
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->functions[$functionName])) {
            // prevent recursion
            $this->functions[$functionName] = true;

            if ($shapeMember->getShape() instanceof StructureShape || $shapeMember->getShape() instanceof ListShape || $shapeMember->getShape() instanceof MapShape) {
                $listAccessorRequired = true;
                $body = '
                    $items = [];
                    foreach (INPUT_PROPERTY as $item) {
                       $items[] = LIST_ACCESSOR;
                    }

                    return $items;';
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

                    return $items;';
            }

            $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                'LIST_ACCESSOR' => $this->parseElement('$item', $shapeMember->getShape(), $listAccessorRequired),
                'INPUT_PROPERTY' => $shape->isFlattened() ? '$json' : '$json' . ($shapeMember->getLocationName() ? '->' . $shapeMember->getLocationName() : ''),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : 'empty(INPUT) ? [] : $this->FUNCTION_NAME(INPUT)', [
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function parseResponseMap(MapShape $shape, string $input, bool $required): string
    {
        $shapeValue = $shape->getValue();
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->functions[$functionName])) {
            // prevent recursion
            $this->functions[$functionName] = true;

            if (null === $locationName = $shape->getKey()->getLocationName()) {
                // We need to use array keys
                if ($shapeValue->getShape() instanceof StructureShape) {
                    $body = '
                        $items = [];
                        foreach ($json as $name => $value) {
                           $items[(string) $name] = CLASS::create($value);
                        }

                        return $items;
                    ';

                    $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                        'CLASS' => $shape->getValue()->getShape()->getName(),
                    ]), $shape);
                } else {
                    $body = '
                        $items = [];
                        foreach ($json as $name => $value) {
                           $items[(string) $name] = CODE;
                        }

                        return $items;
                    ';

                    $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                        'CODE' => $this->parseElement('$value', $shapeValue->getShape(), true),
                    ]), $shape);
                }
            } else {
                $inputAccessorName = $this->getInputAccessorName($shapeValue);
                if ($shapeValue->getShape() instanceof StructureShape) {
                    $body = '
                        $items = [];
                        foreach ($json as $item) {
                            $items[$item[MAP_KEY]] = MAP_ACCESSOR;
                        }

                        return $items;
                    ';
                } else {
                    $body = '
                        $items = [];
                        foreach ($json as $item) {
                            $a = MAP_ACCESSOR;
                            if (null !== $a) {
                                $items[$item[MAP_KEY]] = $a;
                            }
                        }

                        return $items;
                    ';
                }

                $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                    'MAP_KEY' => var_export($locationName, true),
                    'MAP_ACCESSOR' => $this->parseElement(sprintf('$item[\'%s\']', $inputAccessorName), $shapeValue->getShape(), false),
                ]), $shape);
            }
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : 'empty(INPUT) ? [] : $this->FUNCTION_NAME(INPUT)', [
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
            ->addParameter('json')
                ->setType('array')
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
