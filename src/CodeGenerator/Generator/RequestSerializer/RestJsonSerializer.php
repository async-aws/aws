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
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Serialize a request body to a nice nested array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class RestJsonSerializer implements Serializer
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    public function generateForMember(StructureMember $member, string $payloadProperty): string
    {
        return <<<PHP
return \$this->$payloadProperty ?? '';
PHP;
    }

    public function generateForShape(Operation $operation, StructureShape $shape): string
    {
        $body = implode("\n", array_map(function (StructureMember $member) {
            if (null !== $member->getLocation() ?? null) {
                return '';
            }
            $shape = $member->getShape();
            if ($member->isRequired() || $shape instanceof ListShape || $shape instanceof MapShape) {
                $body = 'MEMBER_CODE';
                $inputElement = '$this->' . $member->getName();
            } else {
                $body = 'if (null !== $v = INPUT_NAME) {
                        MEMBER_CODE
                    }';
                $inputElement = '$v';
            }

            return strtr($body, [
                'INPUT_NAME' => '$this->' . $member->getName(),
                'MEMBER_CODE' => $this->dumpArrayElement(sprintf('["%s"]', $this->getName($member)), $inputElement, $shape),
            ]);
        }, $shape->getMembers()));

        return strtr('
                $payload = [\'Action\' => OPERATION_NAME, \'Version\' => API_VERSION];
                CHILDREN_CODE

                return json_encode($payload);
            ', [
            'OPERATION_NAME' => \var_export($operation->getName(), true),
            'API_VERSION' => \var_export($operation->getApiVersion(), true),
            'CHILDREN_CODE' => $body,
        ]);
    }

    private function getName(Member $member)
    {
        if (($shape = $member->getShape()) instanceof ListShape && $shape->isFlattened()) {
            return $member->getLocationName() ?? $shape->getMember()->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member');
        }

        return $member->getLocationName() ?? ($member instanceof StructureMember ? $member->getName() : 'member');
    }

    private function dumpArrayElement(string $output, string $input, Shape $shape)
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpArrayStructure($output, $input, $shape);
            case $shape instanceof ListShape:
                return $this->dumpArrayList($output, $input, $shape);
            case $shape instanceof MapShape:
                throw new \RuntimeException('MapShapes are not implemented');
        }

        switch ($shape->getType()) {
            case 'string':
            case 'integer':
                return $this->dumpArrayScalar($output, $input, $shape);
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
        $memberCode = implode("\n", array_map(function (StructureMember $member) use ($output) {
            $shape = $member->getShape();
            if ($member->isRequired() || $shape instanceof ListShape || $shape instanceof MapShape) {
                $body = 'MEMBER_CODE';
                $inputElement = '$input->get' . $member->getName() . '()';
            } else {
                $body = '

                if (null !== $v = INPUT_NAME) {
                        MEMBER_CODE
                    }';
                $inputElement = '$v';
            }

            return strtr($body, [
                'INPUT_NAME' => '$input->get' . $member->getName() . '()',
                'MEMBER_CODE' => $this->dumpArrayElement(sprintf('%s["%s"]', $output, $this->getName($member)), $inputElement, $shape),
            ]);
        }, $shape->getMembers()));

        $replaceData = [
            'INPUT' => $input,
            'CLASS_NAME' => $this->namespaceRegistry->getInput($shape)->getName(),
            'MEMBERS_CODE' => $memberCode,
        ];

        if ('$v' === $input) {
            // No check for null needed
            return strtr('

(static function(CLASS_NAME $input) use (&$payload) {
    MEMBERS_CODE
})(INPUT);',
                $replaceData
            );
        }

        return strtr('

if (null !== INPUT) {
    (static function(CLASS_NAME $input) use (&$payload) {
        MEMBERS_CODE
    })(INPUT);
}',
            $replaceData
        );
    }

    private function dumpArrayList(string $output, string $input, ListShape $shape): string
    {
        $memberShape = $shape->getMember()->getShape();

        return strtr('
(static function(array $input) use (&$payload) {
    foreach ($input as $value) {
        MEMBER_CODE
    }
})(INPUT);',
            [
                'INPUT' => $input,
                'COMMENT' => false !== strpos($input, '->') ? '// ' . $input : '',
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s[]', $output), '$value', $memberShape),
            ]);
    }

    private function dumpArrayScalar(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT;', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT ? "true" : "false";', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayTimestamp(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = INPUT->format(\DateTimeInterface::ATOM);', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }

    private function dumpArrayBlob(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = base64_encode(INPUT);', [
            'OUTPUT' => $output,
            'INPUT' => false === strpos($input, '->') ? $input : $input . ' ?? \'\'',
        ]);
    }
}
