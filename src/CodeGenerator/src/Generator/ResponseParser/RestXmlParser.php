<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
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
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var array<string, Method>
     */
    private $functions = [];

    /**
     * @var list<ClassName>
     */
    private $imports = [];

    /**
     * @var array<string, true>
     */
    private $generatedFunctions = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, TypeGenerator $typeGenerator)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
        $this->typeGenerator = $typeGenerator;
    }

    public function generate(StructureShape $shape, bool $throwOnError = true): ParserResult
    {
        $properties = [];
        $this->functions = [];
        $this->generatedFunctions = [];
        $this->imports = [];
        if (null !== $payload = $shape->getPayload()) {
            $member = $shape->getMember($payload);
            $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                'PROPERTY_ACCESSOR' => $this->parseXmlElement('$data', $member->getShape(), $member->isRequired() || null === $shape->getResultWrapper(), false),
            ]);
        } else {
            $forException = !$throwOnError;
            foreach ($shape->getMembers() as $member) {
                if (\in_array($member->getLocation(), ['header', 'headers'])) {
                    continue;
                }

                // Avoid conflicts with PHP properties. Those AWS members are included in the AWSError anyway.
                if ($forException && \in_array(strtolower($member->getName()), ['code', 'message'])) {
                    continue;
                }

                $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                    'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                    'PROPERTY_ACCESSOR' => $this->parseXmlElement($this->getInputAccessor('$data', $member), $member->getShape(), $member->isRequired(), false),
                ]);
            }
        }

        if (empty($properties)) {
            return new ParserResult('');
        }

        $this->requirementsRegistry->addRequirement('ext-simplexml');

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

    public function generateForPath(StructureShape $shape, string $path, string $output): string
    {
        $body = '$data = new \SimpleXMLElement($response->getContent());';
        if (null !== $wrapper = $shape->getResultWrapper()) {
            $body .= strtr('$data = $data->WRAPPER;' . "\n", ['WRAPPER' => $wrapper]);
        }
        $path = explode('.', $path);
        $accesor = '$data';
        while (\count($path) > 0) {
            $item = array_shift($path);
            $member = $shape->getMember($item);
            $shape = $member->getShape();
            $accesor = $this->getInputAccessor($accesor, $member);
        }

        return $body . strtr('OUTPUT = PATH', [
            'PATH' => $this->parseXmlElement($accesor, $shape, true, false),
            'OUTPUT' => $output,
        ]);
    }

    private function getInputAccessor(string $currentInput, Member $member): string
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

    /**
     * @param bool $inObject whether the element is building an ObjectValue
     */
    private function parseXmlElement(string $input, Shape $shape, bool $required, bool $inObject): string
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseXmlResponseList($shape, $input, $required, $inObject);
            case $shape instanceof StructureShape:
                return $this->parseXmlResponseStructure($shape, $input, $required);
            case $shape instanceof MapShape:
                return $this->parseXmlResponseMap($shape, $input, $required, $inObject);
            case $shape instanceof DocumentShape:
                throw new \LogicException('Document shapes are not supported in the XML parser for now.');
        }

        switch ($shape->getType()) {
            case 'string':
                return $this->parseXmlResponseString($input, $required);
            case 'long':
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

        throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseXmlResponseStructure(StructureShape $shape, string $input, bool $required): string
    {
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

            $properties = [];
            foreach ($shape->getMembers() as $member) {
                $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                    'PROPERTY_NAME' => var_export($member->getName(), true),
                    'PROPERTY_ACCESSOR' => $this->parseXmlElement($this->getInputAccessor('$xml', $member), $member->getShape(), $member->isRequired(), true),
                ]);
            }

            $body = 'return new CLASS_NAME([
                PROPERTIES
            ]);';

            $className = $this->namespaceRegistry->getObject($shape);
            $this->imports[] = $className;

            $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                'CLASS_NAME' => $className->getName(),
                'PROPERTIES' => implode("\n", $properties),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : '0 === INPUT->count() ? null : $this->FUNCTION_NAME(INPUT)', [
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function parseXmlResponseString(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(string) INPUT', ['INPUT' => $input]);
        }

        return strtr('(null !== $v = INPUT[0]) ? (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseInteger(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(int) (string) INPUT', ['INPUT' => $input]);
        }

        return strtr('(null !== $v = INPUT[0]) ? (int) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseFloat(string $input, bool $required): string
    {
        if ($required) {
            return strtr('(float) (string) INPUT', ['INPUT' => $input]);
        }

        return strtr('(null !== $v = INPUT[0]) ? (float) (string) $v : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBool(string $input, bool $required): string
    {
        $this->requirementsRegistry->addRequirement('ext-filter');

        if ($required) {
            return strtr('filter_var((string) INPUT, FILTER_VALIDATE_BOOLEAN)', ['INPUT' => $input]);
        }

        return strtr('(null !== $v = INPUT[0]) ? filter_var((string) $v, FILTER_VALIDATE_BOOLEAN) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseBlob(string $input, bool $required): string
    {
        if ($required) {
            return strtr('base64_decode((string) INPUT)', ['INPUT' => $input]);
        }

        return strtr('(null !== $v = INPUT[0]) ? base64_decode((string) $v) : null', ['INPUT' => $input]);
    }

    private function parseXmlResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        $format = $shape->get('timestampFormat') ?? '';
        switch ($format) {
            case '':
                $body = 'new \DateTimeImmutable((string) INPUT)';

                break;
            default:
                throw new \RuntimeException(\sprintf('Timestamp format %s is not yet implemented', $format));
        }

        if (!$required) {
            $body = '(null !== $v = INPUT[0]) ? ' . strtr($body, ['INPUT' => '$v']) . ' : null';
        }

        return strtr($body, ['INPUT' => $input]);
    }

    private function parseXmlResponseList(ListShape $shape, string $input, bool $required, bool $inObject): string
    {
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

            $shapeMember = $shape->getMember();
            if ($shapeMember->getShape() instanceof ListShape || $shapeMember->getShape() instanceof MapShape) {
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
            } else {
                $listAccessorRequired = true;

                $body = '
                    $items = [];
                    foreach (INPUT_PROPERTY as $item) {
                       $items[] = LIST_ACCESSOR;
                    }

                    return $items;
                ';
            }

            $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                'LIST_ACCESSOR' => $this->parseXmlElement('$item', $shapeMember->getShape(), $listAccessorRequired, $inObject),
                'INPUT_PROPERTY' => $shape->isFlattened() ? '$xml' : ('$xml->' . ($shapeMember->getLocationName() ? $shapeMember->getLocationName() : 'member')),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : '(0 === ($v = INPUT)->count()) ? EMPTY : $this->FUNCTION_NAME($v)', [
            'EMPTY' => !$inObject ? '[]' : 'null',
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function parseXmlResponseMap(MapShape $shape, string $input, bool $required, bool $inObject): string
    {
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

            $shapeValue = $shape->getValue();
            $body = '
                $items = [];
                foreach (INPUT as $item) {
                    $a = $item->MAP_VALUE;
                    $items[$item->MAP_KEY->__toString()] = MAP_ACCESSOR;
                }

                return $items;
            ';

            $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                'INPUT' => $shape->isFlattened() ? '$xml' : '$xml->entry',
                'MAP_KEY' => $shape->getKey()->getLocationName() ?? 'key',
                'MAP_VALUE' => $shape->getValue()->getLocationName() ?? 'value',
                'MAP_ACCESSOR' => $this->parseXmlElement('$a', $shapeValue->getShape(), true, $inObject),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : '(0 === ($v = INPUT)->count()) ? EMPTY : $this->FUNCTION_NAME($v)', [
            'EMPTY' => !$inObject ? '[]' : 'null',
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

        [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($shape, false);
        $method
            ->setReturnType($returnType)
            ->setComment('@return ' . $parameterType);
        $this->imports = array_merge($this->imports, $memberClassNames);

        return $method;
    }
}
