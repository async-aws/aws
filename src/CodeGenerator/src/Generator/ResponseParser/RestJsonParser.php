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
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Visibility;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class RestJsonParser implements Parser
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
     * @var array<string, true>
     */
    private $generatedFunctions = [];

    /**
     * @var list<ClassName>
     */
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

        $forException = !$throwOnError;

        $properties = [];
        $this->functions = [];
        $this->generatedFunctions = [];
        $this->imports = [];
        foreach ($shape->getMembers() as $member) {
            if (\in_array($member->getLocation(), ['header', 'headers'])) {
                continue;
            }

            // Avoid conflicts with PHP properties. Those AWS members are included in the AWSError anyway.
            if ($forException && \in_array(strtolower($member->getName()), ['code', 'message'])) {
                continue;
            }

            if (!$member->isNullable() && !$member->isRequired()) {
                $properties[] = strtr('if (null !== $v = (PROPERTY_ACCESSOR)) {
                        $this->PROPERTY_NAME = $v;
                    }', [
                    'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                    'PROPERTY_ACCESSOR' => $this->parseElement(\sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired(), false),
                ]);
            } else {
                $properties[] = strtr('$this->PROPERTY_NAME = PROPERTY_ACCESSOR;', [
                    'PROPERTY_NAME' => GeneratorHelper::normalizeName($member->getName()),
                    'PROPERTY_ACCESSOR' => $this->parseElement(\sprintf('$data[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired(), false),
                ]);
            }
        }

        if (empty($properties)) {
            return new ParserResult('');
        }

        $body = '$data = $response->toArray(' . ($throwOnError ? '' : 'false') . ');' . "\n";
        $body .= "\n" . implode("\n", $properties);

        return new ParserResult($body, $this->imports, $this->functions);
    }

    public function generateForPath(StructureShape $shape, string $path, string $output): string
    {
        if (null !== $wrapper = $shape->getResultWrapper()) {
            $body = '$data = $response->toArray();' . "\n";
            $body .= strtr('$data = $data[WRAPPER];' . "\n", ['WRAPPER' => var_export($wrapper, true)]);
            $input = '$data';
        } else {
            $body = '';
            $input = '$response->toArray()';
        }
        $path = explode('.', $path);
        $accesor = '';
        while (\count($path) > 0) {
            $item = array_shift($path);
            $member = $shape->getMember($item);
            $shape = $member->getShape();
            $accesor .= '[' . var_export($this->getInputAccessorName($member), true) . ']';
        }

        return $body . strtr('OUTPUT = INPUTPATH ?? null', [
            'INPUT' => $input,
            'PATH' => $accesor,
            'OUTPUT' => $output,
        ]);
    }

    protected function parseResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        $format = $shape->get('timestampFormat') ?? 'unixTimestamp';
        switch ($format) {
            case 'unixTimestamp':
                $body = '\DateTimeImmutable::createFromFormat("U.u", sprintf("%.6F", INPUT))';

                break;
            case 'iso8601':
                $body = '\DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, INPUT)';

                break;
            default:
                throw new \RuntimeException(\sprintf('Timestamp format %s is not yet implemented', $format));
        }

        if ($required) {
            $body = '/** @var \DateTimeImmutable $d */ $d = ' . $body;
        } else {
            $body = 'isset(INPUT) && ($d = ' . $body . ') ? $d : null';
        }

        return strtr($body, ['INPUT' => $input]);
    }

    private function getInputAccessorName(Member $member): string
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

    /**
     * @param bool $inObject whether the element is building an ObjectValue
     */
    private function parseElement(string $input, Shape $shape, bool $required, bool $inObject): string
    {
        switch (true) {
            case $shape instanceof ListShape:
                return $this->parseResponseList($shape, $input, $required, $inObject);
            case $shape instanceof StructureShape:
                return $this->parseResponseStructure($shape, $input, $required);
            case $shape instanceof MapShape:
                return $this->parseResponseMap($shape, $input, $required, $inObject);
            case $shape instanceof DocumentShape:
                return $this->parseResponseDocument($input, $required);
        }

        switch ($shape->getType()) {
            case 'string':
                return $this->parseResponseString($input, $required);
            case 'long':
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

        throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function parseResponseStructure(StructureShape $shape, string $input, bool $required): string
    {
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

            $properties = [];
            foreach ($shape->getMembers() as $member) {
                $properties[] = strtr('PROPERTY_NAME => PROPERTY_ACCESSOR,', [
                    'PROPERTY_NAME' => var_export($member->getName(), true),
                    'PROPERTY_ACCESSOR' => $this->parseElement(\sprintf('$json[\'%s\']', $this->getInputAccessorName($member)), $member->getShape(), $member->isRequired(), true),
                ]);
            }

            $body = 'return new CLASS_NAME([
                PROPERTIES
            ]);';

            $className = $this->namespaceRegistry->getObject($shape);
            $this->imports[] = $className;

            $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                'INPUT' => $input,
                'CLASS_NAME' => $className->getName(),
                'PROPERTIES' => implode("\n", $properties),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : 'empty(INPUT) ? null : $this->FUNCTION_NAME(INPUT)', [
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
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

    private function parseResponseDocument(string $input, bool $required): string
    {
        if ($required) {
            return strtr('INPUT', ['INPUT' => $input]);
        }

        return strtr('INPUT ?? null', ['INPUT' => $input]);
    }

    private function parseResponseList(ListShape $shape, string $input, bool $required, bool $inObject): string
    {
        $shapeMember = $shape->getMember();
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

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
                'LIST_ACCESSOR' => $this->parseElement('$item', $shapeMember->getShape(), $listAccessorRequired, $inObject),
                'INPUT_PROPERTY' => $shape->isFlattened() ? '$json' : '$json' . ($shapeMember->getLocationName() ? '->' . $shapeMember->getLocationName() : ''),
            ]), $shape);
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : 'EMPTY_METHOD(INPUT) ? EMPTY : $this->FUNCTION_NAME(INPUT)', [
            'EMPTY_METHOD' => $inObject ? '!isset' : 'empty',
            'EMPTY' => !$inObject ? '[]' : 'null',
            'INPUT' => $required && !$inObject ? "$input ?? []" : $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function parseResponseMap(MapShape $shape, string $input, bool $required, bool $inObject): string
    {
        $shapeValue = $shape->getValue();
        $functionName = 'populateResult' . ucfirst($shape->getName());
        if (!isset($this->generatedFunctions[$functionName])) {
            // prevent recursion
            $this->generatedFunctions[$functionName] = true;

            if (null === $locationName = $shape->getKey()->getLocationName()) {
                // We need to use array keys
                if ($shapeValue->getShape() instanceof StructureShape) {
                    $body = '
                        $items = [];
                        foreach ($json as $name => $value) {
                           $items[(string) $name] = BUILDER_CODE;
                        }

                        return $items;
                    ';

                    $this->functions[$functionName] = $this->createPopulateMethod($functionName, strtr($body, [
                        'BUILDER_CODE' => $this->parseResponseStructure($shapeValue->getShape(), '$value', true),
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
                        'CODE' => $this->parseElement('$value', $shapeValue->getShape(), true, $inObject),
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
                    'MAP_ACCESSOR' => $this->parseElement(\sprintf('$item[\'%s\']', $inputAccessorName), $shapeValue->getShape(), false, $inObject),
                ]), $shape);
            }
        }

        return strtr($required ? '$this->FUNCTION_NAME(INPUT)' : 'EMPTY_METHOD(INPUT) ? EMPTY : $this->FUNCTION_NAME(INPUT)', [
            'EMPTY_METHOD' => $inObject ? '!isset' : 'empty',
            'EMPTY' => !$inObject ? '[]' : 'null',
            'INPUT' => $input,
            'FUNCTION_NAME' => $functionName,
        ]);
    }

    private function createPopulateMethod(string $functionName, string $body, Shape $shape): Method
    {
        $method = new Method($functionName);
        $method->setVisibility(Visibility::Private)
            ->setBody($body)
            ->addParameter('json')
                ->setType('array')
        ;

        [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($shape);
        $method
            ->setReturnType($returnType)
            ->setComment('@return ' . $parameterType);
        $this->imports = array_merge($this->imports, $memberClassNames);

        return $method;
    }
}
