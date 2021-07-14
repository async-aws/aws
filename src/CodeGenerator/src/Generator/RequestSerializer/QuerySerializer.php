<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Serialize a request body to a flattened array with "." as separator.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class QuerySerializer implements Serializer
{
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function getHeaders(Operation $operation): string
    {
        return '["content-type" => "application/x-www-form-urlencoded"]';
    }

    public function generateRequestBody(Operation $operation, StructureShape $shape): array
    {
        if (null !== $payloadProperty = $shape->getPayload()) {
            if ($shape->getMember($payloadProperty)->isRequired()) {
                $body = 'if (null === $v = $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                }
                $body = $v;';
            } else {
                $body = '$body = $this->PROPERTY ?? "";';
            }

            return [strtr($body, [
                'PROPERTY' => GeneratorHelper::normalizeName($payloadProperty),
                'NAME' => $payloadProperty,
            ]), false];
        }

        return [strtr('$body = http_build_query([\'Action\' => OPERATION_NAME, \'Version\' => API_VERSION] + $this->requestBody(), \'\', \'&\', \PHP_QUERY_RFC1738);', [
            'OPERATION_NAME' => var_export($operation->getName(), true),
            'API_VERSION' => var_export($operation->getApiVersion(), true),
        ]), true];
    }

    public function generateRequestBuilder(StructureShape $shape): array
    {
        $body = implode("\n", array_map(function (StructureMember $member) {
            if (null !== $member->getLocation()) {
                return '';
            }
            $shape = $member->getShape();
            if ($member->isRequired()) {
                $body = 'if (null === $v = $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "NAME" for "%s". The value cannot be null.\', __CLASS__));
                }
                MEMBER_CODE';
                $inputElement = '$v';
            } else {
                $body = 'if (null !== $v = $this->PROPERTY) {
                    MEMBER_CODE
                }';
                $inputElement = '$v';
            }

            $deprecation = '';
            if ($member->isDeprecated()) {
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }

            return strtr($body, [
                'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                'NAME' => $member->getName(),
                'MEMBER_CODE' => $deprecation . $this->dumpArrayElement($name = $this->getName($member), $inputElement, $name, $shape),
            ]);
        }, $shape->getMembers()));

        return ['array', strtr('
                $payload = [];
                CHILDREN_CODE

                return $payload;
            ', [
            'CHILDREN_CODE' => $body,
        ])];
    }

    private function getQueryName(Member $member, string $default): string
    {
        if (null !== $member->getQueryName()) {
            return $member->getQueryName();
        }
        if (null !== $member->getLocationName()) {
            return $member->getLocationName();
        }
        $shape = $member->getShape();
        if ($member->isFlattened() && $shape instanceof ListShape && null !== $memberLocation = $shape->getMember()->getLocationName()) {
            return $memberLocation;
        }

        return $default;
    }

    private function getName(Member $member): string
    {
        if ($member instanceof StructureMember) {
            $name = $this->getQueryName($member, $member->getName());
        } else {
            throw new \RuntimeException('Guessing the name fot this member not yet implemented');
        }

        $shape = $member->getShape();
        if ($shape instanceof ListShape) {
            if (!$member->isFlattened() && !$shape->isFlattened()) {
                return $name . '.' . ($shape->getMember()->getLocationName() ?? 'member');
            }

            return $this->getQueryName($shape->getMember(), 'FIXME');
        }

        if ($shape instanceof MapShape) {
            return $name . ($shape->isFlattened() ? '' : '.entry');
        }

        return $name;
    }

    private function dumpArrayElement(string $output, string $input, string $contextProperty, Shape $shape)
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpArrayStructure($output, $input, $shape);
            case $shape instanceof ListShape:
                return $this->dumpArrayList($output, $input, $contextProperty, $shape);
            case $shape instanceof MapShape:
                return $this->dumpArrayMap($output, $input, $contextProperty, $shape);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'integer':
            case 'double':
                return $this->dumpArrayScalar($output, $input, $contextProperty, $shape);
            case 'boolean':
                return $this->dumpArrayBoolean($output, $input, $shape);
            case 'timestamp':
                return $this->dumpArrayTimestamp($output, $input, $shape);
            case 'blob':
                return $this->dumpArrayBlob($output, $input, $shape);
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function dumpArrayStructure(string $output, string $input, StructureShape $shape): string
    {
        return strtr('foreach (INPUT->requestBody() as $bodyKey => $bodyValue) {
                $payload["OUTPUT.$bodyKey"] = $bodyValue;
            }
        ', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayMap(string $output, string $input, string $contextProperty, MapShape $shape): string
    {
        $mapKeyShape = $shape->getKey()->getShape();
        if (!empty($mapKeyShape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($mapKeyShape);
            $validateEnum = strtr('if (!ENUM_CLASS::exists($mapKey)) {
                    throw new InvalidArgument(sprintf(\'Invalid key for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, $mapKey));
                }', [
                'ENUM_CLASS' => $enumClassName->getName(),
            ]);
        } else {
            $validateEnum = '';
        }

        return strtr('
            $index = 0;
            foreach (INPUT as $mapKey => $mapValue) {
                VALIDATE_ENUM
                $index++;
                $payload["OUTPUT_KEY"] = $mapKey;
                MEMBER_CODE
            }
        ',
            [
                'INPUT' => $input,
                'VALIDATE_ENUM' => $validateEnum,
                'OUTPUT_KEY' => sprintf('%s.$index.%s', $output, $this->getQueryName($shape->getKey(), 'key')),
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s.$index.%s', $output, $this->getQueryName($shape->getValue(), 'value')), '$mapValue', $contextProperty, $shape->getValue()->getShape()),
            ]);
    }

    private function dumpArrayList(string $output, string $input, string $contextProperty, ListShape $shape): string
    {
        $memberShape = $shape->getMember()->getShape();

        return strtr('
            $index = 0;
            foreach (INPUT as $mapValue) {
                $index++;
                MEMBER_CODE
            }
        ',
            [
                'INPUT' => $input,
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s.$index', $output), '$mapValue', $contextProperty, $memberShape),
            ]);
    }

    private function dumpArrayScalar(string $output, string $input, string $contextProperty, Shape $shape): string
    {
        $body = '$payload["OUTPUT"] = INPUT;';
        $replacements = [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ];
        if (!empty($shape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($shape);
            $body = 'if (!ENUM_CLASS::exists(INPUT)) {
                    throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, INPUT));
                }
            ' . $body;
            $replacements += [
                'ENUM_CLASS' => $enumClassName->getName(),
                'PROPERTY' => $contextProperty,
            ];
        }

        return strtr($body, $replacements);
    }

    private function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT ? "true" : "false";', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayTimestamp(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT->format(\DateTimeInterface::ATOM);', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBlob(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = base64_encode(INPUT);', [
            'OUTPUT' => $output,
            'INPUT' => false === strpos($input, '->') ? $input : $input . ' ?? \'\'',
        ]);
    }
}
