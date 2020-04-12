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
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
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
    private const MARKER = 'self::fail(\'Not implemented\');';

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
        $this->generateClient($operation);
        $this->generateInput($operation, $operation->getInput());
        if (null !== $result = $operation->getOutput()) {
            $this->generateResult($operation, $result);
        }
    }

    private function generateInput(Operation $operation, StructureShape $shape)
    {
        $className = $this->namespaceRegistry->getInputUnitTest($shape);
        $methodName = 'testRequest';

        if (\class_exists($className->getFqdn())) {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];

            if ($class->hasMethod($methodName) || !$operation->hasBody()) {
                return;
            }
        } else {
            [$namespace, $class] = $this->createTestClass($className, $this->namespaceRegistry->getInput($shape));
        }

        $class->setExtends([TestCase::class]);
        $namespace->addUse(TestCase::class);

        $exampleInput = $operation->getExample()->getInput();
        $comment = $exampleInput ? '// see example-1.json from SDK' : '// see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_OPERATION.html';

        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
                $stub = substr(var_export($this->arrayToXml($exampleInput ?? ['change' => 'it']), true), 1, -1);
                $contenType = 'application/xml';

                break;
            case 'rest-json':
                $stub = substr(var_export(\json_encode($exampleInput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true), 1, -1);
                $contenType = 'application/json';

                break;
            case 'json':
                $stub = substr(var_export(\json_encode($exampleInput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true), 1, -1);
                $contenType = 'application/x-amz-json-1.0';

                break;
            case 'query':
                $stub = substr(var_export($exampleInput ? $exampleInput : "
    Action={$operation->getName()}
    &Version={$operation->getApiVersion()}
", true), 1, -1);
                $contenType = 'application/x-www-form-urlencoded';

                break;
            default:
                throw new \InvalidArgumentException(sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));
        }

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                $input = INPUT_CONSTRUCTOR;

                ' . $comment . '
                $expected = \'
                    METHOD / HTTP/1.0
                    Content-Type: CONTENT_TYPE

                    STUB
                \';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
            ', [
                'MARKER' => self::MARKER,
                'INPUT_CONSTRUCTOR' => $this->getInputCode($namespace, $operation->getInput()),
                'SERVICE' => \strtolower($operation->getService()->getName()),
                'METHOD' => $operation->getHttpMethod(),
                'OPERATION' => $operation->getName(),
                'CONTENT_TYPE' => $contenType,
                'STUB' => trim($stub),
            ]));

        $this->fileWriter->write($namespace);
    }

    private function generateResult(Operation $operation, StructureShape $shape)
    {
        $className = $this->namespaceRegistry->getResultUnitTest($shape);
        $resultClass = $this->namespaceRegistry->getResult($shape);
        $methodName = 'test' . $shape->getName();

        if (\class_exists($className->getFqdn())) {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];

            if ($class->hasMethod($methodName)) {
                return;
            }
        } else {
            [$namespace, $class] = $this->createTestClass($className, $this->namespaceRegistry->getResult($shape));
        }

        $class->setExtends([TestCase::class]);
        $namespace->addUse(TestCase::class);
        $namespace->addUse($resultClass->getFqdn());
        $namespace->addUse(SimpleMockedResponse::class);
        $namespace->addUse(MockHttpClient::class);

        $exampleOutput = $operation->getExample()->getOutput();
        $comment = $exampleOutput ? '// see example-1.json from SDK' : '// see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_OPERATION.html';
        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
            case 'query':
                $stub = sprintf('$response = new SimpleMockedResponse(%s);', var_export($this->arrayToXml($exampleOutput ?? ['change' => 'it']), true));

                break;
            case 'rest-json':
            case 'json':
                $stub = sprintf('$response = new SimpleMockedResponse(%s);', var_export(\json_encode($exampleOutput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true));

                break;
            default:
                throw new \InvalidArgumentException(sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));
        }

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                ' . $comment . '
                STUB

                $client = new MockHttpClient($response);
                $result = new INPUT_CLASS(new Response($client->request(\'POST\', \'http://localhost\'), $client));

                ASSERT
            ', [
                'MARKER' => self::MARKER,
                'INPUT_CLASS' => $resultClass->getName(),
                'SERVICE' => \strtolower($operation->getService()->getName()),
                'OPERATION' => $operation->getName(),
                'STUB' => $stub,
                'ASSERT' => $this->getResultAssert($operation->getOutput()),
            ]));
        $namespace->addUse(Response::class);

        $this->fileWriter->write($namespace);
    }

    private function generateIntegration(Operation $operation)
    {
        $className = $this->namespaceRegistry->getIntegrationTest($operation->getService());
        $inputClassName = $this->namespaceRegistry->getInput($operation->getInput());
        $methodName = 'test' . $operation->getName();

        if (\class_exists($className->getFqdn())) {
            $namespace = ClassFactory::fromExistingClass($className->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];
            if ($class->hasMethod($methodName)) {
                return;
            }
        } else {
            [$namespace, $class] = $this->createClientTestClass($className, $operation);
        }

        $class->setExtends([TestCase::class]);
        $namespace->addUse(TestCase::class);
        $namespace->addUse($inputClassName->getFqdn());

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                $client = $this->getClient();

                $input = INPUT_CONSTRUCTOR;
                $result = $client->METHOD($input);

                $result->resolve();

                RESULT_ASSERT
            ', [
                'INPUT_CONSTRUCTOR' => $this->getInputCode($namespace, $operation->getInput()),
                'METHOD' => $operation->getName(),
                'RESULT_ASSERT' => $operation->getOutput() ? $this->getResultAssert($operation->getOutput()) : '',
            ]));

        $this->fileWriter->write($namespace);
    }

    private function getInputCode(PhpNamespace $namespace, Shape $shape, bool $includeOptionalParameters = true, bool $nested = false): string
    {
        switch (true) {
            case $shape instanceof StructureShape:
                if ($nested && ($shape instanceof StructureShape || $shape instanceof ListShape || $shape instanceof MapShape)) {
                    // TODO make sure the output has some smart code instead. Like a loop or recursion
                    return '""';
                }

                if ('Input' === substr($shape->getName(), -5)) {
                    $className = $this->namespaceRegistry->getInput($shape);
                } else {
                    $className = $this->namespaceRegistry->getObject($shape);
                }
                $namespace->addUse($className->getFqdn());

                return strtr('new INPUT_CLASS([
                    INPUT_ARGUMENTS
                ])', [
                    'INPUT_CLASS' => $className->getName(),
                    'INPUT_ARGUMENTS' => \implode("\n", \array_map(function (StructureMember $member) use ($namespace, $includeOptionalParameters) {
                        if ($member->isRequired() || $includeOptionalParameters) {
                            return sprintf('%s => %s,', \var_export($member->getName(), true), $this->getInputCode($namespace, $member->getShape()));
                        }
                    }, $shape->getMembers())),
                ]);
            case $shape instanceof ListShape:
                return strtr('[INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($namespace, $shape->getMember()->getShape()),
                ]);
            case $shape instanceof MapShape:
                return strtr('["change me" => INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($namespace, $shape->getValue()->getShape(), $includeOptionalParameters, true),
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

    /**
     * Generate client unit tests.
     */
    private function generateClient(Operation $operation): void
    {
        $clientName = $this->namespaceRegistry->getClient($operation->getService());
        $clientTestName = $this->namespaceRegistry->getClientTest($operation->getService());
        $methodName = 'test' . $operation->getName();

        try {
            $namespace = ClassFactory::fromExistingClass($clientTestName->getFqdn());
            $classes = $namespace->getClasses();
            $class = $classes[\array_key_first($classes)];

            if ($class->hasMethod($methodName)) {
                return;
            }
        } catch (\ReflectionException $e) {
            [$namespace, $class] = $this->createTestClass($clientTestName, $clientName);
        }

        if (null === $operation->getOutput()) {
            $output = ClassName::create('AsyncAws\\Core', 'Result');
        } else {
            $output = $this->namespaceRegistry->getResult($operation->getOutput());
        }
        $class->setExtends([TestCase::class]);
        $namespace->addUse(TestCase::class);
        $namespace->addUse(MockHttpClient::class);
        $namespace->addUse(NullProvider::class);
        $namespace->addUse($output->getFqdn());

        $class->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                $client = new CLASS_NAME([], new NullProvider(), new MockHttpClient());

                $input = INPUT_CONSTRUCTOR;
                $result = $client->METHOD($input);

                self::assertInstanceOf(RESULT::class, $result);
                self::assertFalse($result->info()[\'resolved\']);

            ', [
                'CLASS_NAME' => $clientName->getName(),
                'INPUT_CONSTRUCTOR' => $this->getInputCode($namespace, $operation->getInput(), false),
                'RESULT' => $output->getName(),
                'METHOD' => $operation->getName(),
            ]));

        $this->fileWriter->write($namespace);
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

        $namespace->addUse(TestCase::class);

        $class = $namespace->addClass($testClassName->getName());
        $class->addExtend(TestCase::class);

        return [$namespace, $class];
    }

    private function createClientTestClass(ClassName $testClassName, Operation $operation): array
    {
        $clientClassName = $this->namespaceRegistry->getClient($operation->getService());

        $namespace = new PhpNamespace($testClassName->getNamespace());
        $namespace->addUse(TestCase::class);
        $namespace->addUse($clientClassName->getFqdn());
        $namespace->addUse(NullProvider::class);

        $class = $namespace->addClass($testClassName->getName());
        $class->addExtend(TestCase::class);
        $class->addMethod('getClient')
            ->setVisibility(ClassType::VISIBILITY_PRIVATE)
            ->setReturnType($clientClassName->getFqdn())
            ->setBody(strtr('
    MARKER

    return new CLASS_NAME([
        \'endpoint\' => \'http://localhost\',
    ], new NullProvider());
            ', [
                'MARKER' => self::MARKER,
                'CLASS_NAME' => $clientClassName->getName(),
            ]));

        $this->fileWriter->write($namespace);

        return [$namespace, $class];
    }

    private function arrayToXml(array $data): string
    {
        $xml = new \SimpleXMLElement('<root/>');
        $f = function (\SimpleXMLElement $element, array $data) use (&$f) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    if (!is_numeric($key)) {
                        $f($element->addChild($key), $value);
                    } else {
                        $f($element->addChild("key_$key"), $value);
                    }
                } else {
                    if (!is_numeric($key)) {
                        $element->addChild($key, (string) $value);
                    } else {
                        $element->addChild("key_$key", $value);
                    }
                }
            }
        };
        $f($xml, $data);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml->asXML());
        $dom->formatOutput = true;

        return $dom->saveXml($dom->firstChild->firstChild);
    }
}

// Because AsyncAws use symfony/phpunit-bridge and don't requires phpunit/phpunit, this class may not exits but is required by the generator
if (!\class_exists(PHPUnitTestCase::class)) {
    class DummyTestCase
    {
    }
    \class_alias(DummyTestCase::class, PHPUnitTestCase::class);
}
