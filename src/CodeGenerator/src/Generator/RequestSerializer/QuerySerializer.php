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

/**
 * Serialize a request body to a flattened array with "." as separator.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class QuerySerializer implements Serializer
{
    public function getContentType(): string
    {
        return 'application/x-www-form-urlencoded';
    }

    public function generateRequestBody(Operation $operation, StructureShape $shape): array
    {
        if (null !== $payloadProperty = $shape->getPayload()) {
            return ['$body = $this->' . $payloadProperty . ' ?? "";', false];
        }

        return [strtr('$body = http_build_query([\'Action\' => OPERATION_NAME, \'Version\' => API_VERSION] + $this->requestBody(), \'\', \'&\', \PHP_QUERY_RFC1738);', [
            'OPERATION_NAME' => \var_export($operation->getName(), true),
            'API_VERSION' => \var_export($operation->getApiVersion(), true),
        ]), true];
    }

    public function generateRequestBuilder(StructureShape $shape): array
    {
        $body = implode("\n", array_map(function (StructureMember $member) {
            if (null !== $member->getLocation()) {
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
                'MEMBER_CODE' => $this->dumpArrayElement($this->getName($member), $inputElement, $shape),
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
            $name = 'FIXME';
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

    private function dumpArrayElement(string $output, string $input, Shape $shape)
    {
        switch (true) {
            case $shape instanceof StructureShape:
                return $this->dumpArrayStructure($output, $input, $shape);
            case $shape instanceof ListShape:
                return $this->dumpArrayList($output, $input, $shape);
            case $shape instanceof MapShape:
                return $this->dumpArrayMap($output, $input, $shape);
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
        $memberCode = strtr('
            foreach ($v->requestBody() as $bodyKey => $bodyValue) {
                $payload["OUTPUT.$bodyKey"] = $bodyValue;
            }
        ', ['OUTPUT' => $output]);

        if ('$v' === $input) {
            $body = 'MEMBER_CODE';
        } else {
            $body = 'if (null !== $v = INPUT) {
                MEMBER_CODE
            }';
        }

        return strtr($body, [
            'INPUT' => $input,
            'MEMBER_CODE' => $memberCode,
        ]);
    }

    private function dumpArrayMap(string $output, string $input, MapShape $shape): string
    {
        return strtr('
            $index = 0;
            foreach (INPUT as $mapKey => $listValue) {
                $index++;
                $payload["OUTPUT_KEY"] = $mapKey;
                MEMBER_CODE
            }
        ',
            [
                'INPUT' => $input,
                'OUTPUT_KEY' => sprintf('%s.{$index}.%s', $output, $this->getQueryName($shape->getKey(), 'key')),
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s.{$index}.%s', $output, $this->getQueryName($shape->getValue(), 'value')), '$listValue', $shape->getValue()->getShape()),
            ]);
    }

    private function dumpArrayList(string $output, string $input, ListShape $shape): string
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
                'MEMBER_CODE' => $memberCode = $this->dumpArrayElement(sprintf('%s.{$index}', $output), '$mapValue', $memberShape),
            ]);
    }

    private function dumpArrayScalar(string $output, string $input, Shape $shape): string
    {
        return strtr('$payload["OUTPUT"] = INPUT;', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
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
