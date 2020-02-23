<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * Generate API test methods.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class TestGenerator
{
    private const MARKER = 'self::markTestIncomplete(\'Not implemented\');';

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
    }

    /**
     * Update the API test client.
     */
    public function generate(Operation $operation): void
    {
        $this->generateIntegration($operation);
        $this->generateInput($operation, $operation->getInput());
        if (null !== $result = $operation->getOutput()) {
            $this->generateResult($operation, $result);
        }
    }

    private function generateInput(Operation $operation, StructureShape $shape)
    {
        $className = $this->namespaceRegistry->getInputUnitTest($shape);
        $methodName = 'testRequestBody';

        try {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];

            if ($class->hasMethod($methodName)) {
                return;
            }
        } catch (\ReflectionException $e) {
            [$namespace, $class] = $this->createTestClass($className, $this->namespaceRegistry->getInput($shape));
        }

        $class->setExtends(['PHPUnit\Framework\TestCase']);
        $namespace->addUse('PHPUnit\Framework\TestCase');

        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
                $stub = '$expected = "<ChangeIt/>";';
                $assert = 'self::assertXmlStringEqualsXmlString($expected, $input->requestBody());';

                break;
            case 'rest-json':
            case 'json':
                $stub = '$expected = \'{"change": "it"}\';';
                $assert = 'self::assertJsonStringEqualsJsonString($expected, $input->requestBody());';

                break;
            case 'query':
                $stub = "\$expected = trim('
Action={$operation->getName()}
&Version={$operation->getApiVersion()}
&ChangeIt=Change+it
                ');";
                $assert = 'self::assertEquals($expected, \str_replace(\'&\', "\n&", $input->requestBody()));';

                break;
            default:
                throw new \InvalidArgumentException(sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));
        }

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                $input = INPUT_CONSTRUCTOR;

                STUB

                ASSERT
            ', [
                'MARKER' => self::MARKER,
                'INPUT_CONSTRUCTOR' => $this->getInputCode($namespace, $operation->getInput()),
                'STUB' => $stub,
                'ASSERT' => $assert,
            ]));

        $this->fileWriter->write($namespace);
    }

    private function generateResult(Operation $operation, StructureShape $shape)
    {
        $className = $this->namespaceRegistry->getResultUnitTest($shape);
        $resultClass = $this->namespaceRegistry->getResult($shape);
        $methodName = 'test' . $shape->getName();

        try {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];

            if ($class->hasMethod($methodName)) {
                return;
            }
        } catch (\ReflectionException $e) {
            [$namespace, $class] = $this->createTestClass($className, $this->namespaceRegistry->getResult($shape));
        }

        $class->setExtends(['PHPUnit\Framework\TestCase']);
        $namespace->addUse('PHPUnit\Framework\TestCase');
        $namespace->addUse($resultClass->getFqdn());
        $namespace->addUse(SimpleMockedResponse::class);
        $namespace->addUse(MockHttpClient::class);

        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
            case 'query':
                $stub = '$response = new SimpleMockedResponse(\'<?xml version="1.0" encoding="UTF-8"?>
    <ChangeIt/>
\');';

                break;
            case 'rest-json':
            case 'json':
            $stub = '$response = new SimpleMockedResponse(\'{"change": "it"}\');';

                break;
            default:
                throw new \InvalidArgumentException(sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));

        }

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                STUB

                $result = new INPUT_CLASS($response, new MockHttpClient());

                ASSERT
            ', [
                'MARKER' => self::MARKER,
                'INPUT_CLASS' => $resultClass->getName(),
                'STUB' => $stub,
                'ASSERT' => $this->getResultAssert($operation->getOutput()),
            ]));

        $this->fileWriter->write($namespace);
    }

    private function generateIntegration(Operation $operation)
    {
        $className = $this->namespaceRegistry->getIntegrationTest($operation->getService());
        $methodName = 'test' . $operation->getName();

        try {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];
            if ($class->hasMethod($methodName)) {
                return;
            }
        } catch (\ReflectionException $e) {
            [$namespace, $class] = $this->createClientTestClass($className, $operation);
        }

        $class->setExtends(['PHPUnit\Framework\TestCase']);

        $inputClassName = $this->namespaceRegistry->getInput($operation->getInput());
        $namespace->addUse($inputClassName->getFqdn());

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                $client = $this->getClient();

                $input = INPUT_CONSTRUCTOR;
                $result = $client->METHOD($input);

                $result->resolve();

                RESULT_ASSERT
            ', [
                'MARKER' => self::MARKER,
                'INPUT_CONSTRUCTOR' => $this->getInputCode($namespace, $operation->getInput()),
                'METHOD' => $operation->getName(),
                'RESULT_ASSERT' => $operation->getOutput() ? $this->getResultAssert($operation->getOutput()) : '',
            ]));

        $this->fileWriter->write($namespace);
    }

    private function getInputCode(PhpNamespace $namespace, Shape $shape): string
    {
        switch (true) {
            case $shape instanceof StructureShape:
                $className = $this->namespaceRegistry->getInput($shape);
                $namespace->addUse($className->getFqdn());

                return strtr('new INPUT_CLASS([
                    INPUT_ARGUMENTS
                ])', [
                    'INPUT_CLASS' => $className->getName(),
                    'INPUT_ARGUMENTS' => \implode("\n", \array_map(function (StructureMember $member) use ($namespace) {
                        return sprintf('%s => %s,', \var_export($member->getName(), true), $this->getInputCode($namespace, $member->getShape()));
                    }, $shape->getMembers())),
                ]);
            case $shape instanceof ListShape:
                return strtr('[INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($namespace, $shape->getMember()->getShape()),
                ]);
            case $shape instanceof MapShape:
                return strtr('["change me" => INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($namespace, $shape->getValue()->getShape()),
                ]);
        }

        switch ($shape->getType()) {
            case 'string':
            case 'blob':
                return \var_export('change me', true);
            case 'integer':
            case 'long':
            case 'float':
                return \var_export(1337, true);
            case 'timestamp':
                return 'new \DateTimeImmutable()';
            case 'boolean':
                return 'false';
        }

        throw new \RuntimeException(sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function getResultAssert(StructureShape $shape): string
    {
        return implode("\n", \array_map(function (StructureMember $member) {
            switch ($member->getShape()->getType()) {
                case 'string':
                    return sprintf('self::assertSame("changeIt", $result->get%s());', $member->getName());
                case 'boolean':
                    return sprintf('self::assertFalse($result->get%s());', $member->getName());
                case 'integer':
                case 'long':
                    return sprintf('self::assertSame(1337, $result->get%s());', $member->getName());
                default:
                    return sprintf('// self::assertTODO(expected, $result->get%s());', $member->getName());
            }
        }, $shape->getMembers()));
    }

    private function createTestClass(ClassName $testClassName, ClassName $sourceClassName): array
    {
        $namespace = new PhpNamespace($testClassName->getNamespace());
        $namespace->addUse($sourceClassName->getFqdn());

        $namespace->addUse('PHPUnit\Framework\TestCase');

        $class = $namespace->addClass($testClassName->getName());
        $class->addExtend('PHPUnit\Framework\TestCase');

        return [$namespace, $class];
    }

    private function createClientTestClass(ClassName $testClassName, Operation $operation): array
    {
        $clientClassName = $this->namespaceRegistry->getClient($operation->getService());

        $namespace = new PhpNamespace($testClassName->getNamespace());
        $namespace->addUse('PHPUnit\Framework\TestCase');
        $namespace->addUse($clientClassName->getFqdn());
        $namespace->addUse(NullProvider::class);

        $class = $namespace->addClass($testClassName->getName());
        $class->addExtend('PHPUnit\Framework\TestCase');
        $class->addMethod('getClient')
            ->setVisibility(ClassType::VISIBILITY_PRIVATE)
            ->setReturnType($clientClassName->getFqdn())
            ->setBody(strtr('
    return new CLASS_NAME([
        \'endpoint\' => \'http://localhost\',
    ], new NullProvider());
            ', [
                'CLASS_NAME' => $clientClassName->getName(),
            ]));

        $this->fileWriter->write($namespace);

        return [$namespace, $class];
    }
}

if (!\class_exists('PHPUnit\Framework\TestCase')) {
    class TestCase
    {
    }
    \class_alias(TestCase::class, 'PHPUnit\Framework\TestCase');
}
