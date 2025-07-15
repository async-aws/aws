<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Nette\PhpGenerator\ClassType;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Log\NullLogger;
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
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
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

    private function generateInput(Operation $operation, StructureShape $shape): void
    {
        $className = $this->namespaceRegistry->getInputUnitTest($shape);
        $methodName = 'testRequest';

        if (class_exists($className->getFqdn())) {
            $classBuilder = $this->classRegistry->register($className->getFqdn(), true);
            if ($classBuilder->hasMethod($methodName) || !$operation->hasBody()) {
                $this->classRegistry->unregister($className->getFqdn());

                return;
            }
        } else {
            $classBuilder = $this->createTestClass($className, $this->namespaceRegistry->getInput($shape));
        }

        $classBuilder->setExtends(TestCase::class);
        $classBuilder->addUse(TestCase::class);

        $exampleInput = $operation->getExample()->getInput();
        $comment = $exampleInput ? '// see example-1.json from SDK' : '// see ' . $operation->getApiReferenceDocumentationUrl();

        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
                $stub = substr(var_export($this->arrayToXml($exampleInput ?? ['change' => 'it']), true), 1, -1);
                $contentType = 'application/xml';

                break;
            case 'rest-json':
                $stub = substr(var_export(json_encode($exampleInput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true), 1, -1);
                $contentType = 'application/json';

                break;
            case 'json':
                $stub = substr(var_export(json_encode($exampleInput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true), 1, -1);
                $contentType = 'application/x-amz-json-' . number_format($operation->getService()->getJsonVersion(), 1);

                break;
            case 'query':
                $stub = substr(var_export($exampleInput ? http_build_query($exampleInput, '', '&', \PHP_QUERY_RFC3986) : "
    Action={$operation->getName()}
    &Version={$operation->getApiVersion()}
", true), 1, -1);
                $contentType = 'application/x-www-form-urlencoded';

                break;
            default:
                throw new \InvalidArgumentException(\sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));
        }

        $classBuilder->addMethod($methodName)
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
                'INPUT_CONSTRUCTOR' => $this->getInputCode($classBuilder, $operation->getInput()),
                'SERVICE' => strtolower($operation->getService()->getName()),
                'METHOD' => $operation->getHttpMethod(),
                'OPERATION' => $operation->getName(),
                'CONTENT_TYPE' => $contentType,
                'STUB' => trim($stub),
            ]));
    }

    private function generateResult(Operation $operation, StructureShape $shape): void
    {
        $className = $this->namespaceRegistry->getResultUnitTest($shape);
        $clientClass = $this->namespaceRegistry->getClient($operation->getService());
        $inputClass = $this->namespaceRegistry->getInput($operation->getInput());
        $resultClass = $this->namespaceRegistry->getResult($shape);
        $methodName = 'test' . $shape->getName();

        if (class_exists($className->getFqdn())) {
            $classBuilder = $this->classRegistry->register($className->getFqdn(), true);
            if ($classBuilder->hasMethod($methodName)) {
                $this->classRegistry->unregister($className->getFqdn());

                return;
            }
        } else {
            $classBuilder = $this->createTestClass($className, $this->namespaceRegistry->getResult($shape));
        }

        $classBuilder->setExtends(TestCase::class);
        $classBuilder->addUse(TestCase::class);
        $classBuilder->addUse($clientClass->getFqdn());
        $classBuilder->addUse($inputClass->getFqdn());
        $classBuilder->addUse($resultClass->getFqdn());
        $classBuilder->addUse(SimpleMockedResponse::class);
        $classBuilder->addUse(MockHttpClient::class);

        $exampleOutput = $operation->getExample()->getOutput();
        $comment = $exampleOutput ? '// see example-1.json from SDK' : '// see ' . $operation->getApiReferenceDocumentationUrl();
        switch ($operation->getService()->getProtocol()) {
            case 'rest-xml':
            case 'query':
                $stub = \sprintf('$response = new SimpleMockedResponse(%s);', var_export($this->arrayToXml($exampleOutput ?? ['change' => 'it']), true));

                break;
            case 'rest-json':
            case 'json':
                $stub = \sprintf('$response = new SimpleMockedResponse(%s);', var_export(json_encode($exampleOutput ?? ['change' => 'it'], \JSON_PRETTY_PRINT), true));

                break;
            default:
                throw new \InvalidArgumentException(\sprintf('unexpected protocol "%s".', $operation->getService()->getProtocol()));
        }

        if (null !== $operation->getPagination()) {
            $resultConstruct = '$result = new RESULT_CLASS(new Response($client->request(\'POST\', \'http://localhost\'), $client, new NullLogger()), new CLIENT_CLASS(), new INPUT_CLASS([]));';
        } else {
            $resultConstruct = '$result = new RESULT_CLASS(new Response($client->request(\'POST\', \'http://localhost\'), $client, new NullLogger()));';
        }
        $classBuilder->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                MARKER

                ' . $comment . '
                STUB

                $client = new MockHttpClient($response);
                ' . $resultConstruct . '

                ASSERT
            ', [
                'MARKER' => self::MARKER,
                'RESULT_CLASS' => $resultClass->getName(),
                'INPUT_CLASS' => $inputClass->getName(),
                'CLIENT_CLASS' => $clientClass->getName(),
                'SERVICE' => strtolower($operation->getService()->getName()),
                'OPERATION' => $operation->getName(),
                'STUB' => $stub,
                'ASSERT' => $this->getResultAssert($operation->getOutput()),
            ]));
        $classBuilder->addUse(Response::class);
        $classBuilder->addUse(NullLogger::class);
    }

    private function generateIntegration(Operation $operation): void
    {
        $className = $this->namespaceRegistry->getIntegrationTest($operation->getService());
        $inputClassName = $this->namespaceRegistry->getInput($operation->getInput());
        $methodName = 'test' . $operation->getMethodName();

        if (class_exists($className->getFqdn())) {
            $classBuilder = $this->classRegistry->register($className->getFqdn(), true);
            if ($classBuilder->hasMethod($methodName)) {
                $this->classRegistry->unregister($className->getFqdn());

                return;
            }
        } else {
            $classBuilder = $this->createClientTestClass($className, $operation);
        }

        $classBuilder->setExtends(TestCase::class);
        $classBuilder->addUse(TestCase::class);
        $classBuilder->addUse($inputClassName->getFqdn());

        $classBuilder->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                $client = $this->getClient();

                $input = INPUT_CONSTRUCTOR;
                $result = $client->METHOD($input);

                $result->resolve();

                RESULT_ASSERT
            ', [
                'INPUT_CONSTRUCTOR' => $this->getInputCode($classBuilder, $operation->getInput()),
                'METHOD' => lcfirst($operation->getMethodName()),
                'RESULT_ASSERT' => $operation->getOutput() ? $this->getResultAssert($operation->getOutput()) : '',
            ]));
    }

    private function getInputCode(ClassBuilder $classBuilder, Shape $shape, bool $includeOptionalParameters = true, array $recursion = []): string
    {
        if (isset($recursion[$shape->getName()])) {
            return '""';
        }
        $recursion[$shape->getName()] = true;

        switch (true) {
            case $shape instanceof StructureShape:
                if (1 === \count($recursion)) {
                    $className = $this->namespaceRegistry->getInput($shape);
                } else {
                    $className = $this->namespaceRegistry->getObject($shape);
                }
                $classBuilder->addUse($className->getFqdn());

                return strtr('new INPUT_CLASS([
                    INPUT_ARGUMENTS
                ])', [
                    'INPUT_CLASS' => $className->getName(),
                    'INPUT_ARGUMENTS' => implode("\n", array_map(function (StructureMember $member) use ($classBuilder, $includeOptionalParameters, $recursion) {
                        if ($member->isRequired() || $includeOptionalParameters) {
                            return \sprintf(
                                '%s => %s,',
                                var_export($member->getName(), true),
                                $this->getInputCode($classBuilder, $member->getShape(), $includeOptionalParameters, $recursion)
                            );
                        }
                    }, $shape->getMembers())),
                ]);
            case $shape instanceof ListShape:
                return strtr('[INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($classBuilder, $shape->getMember()->getShape(), $includeOptionalParameters, $recursion),
                ]);
            case $shape instanceof MapShape:
                return strtr('["change me" => INPUT_ARGUMENTS]', [
                    'INPUT_ARGUMENTS' => $this->getInputCode($classBuilder, $shape->getValue()->getShape(), $includeOptionalParameters, $recursion),
                ]);
            case $shape instanceof DocumentShape:
                return '"change me"';
        }

        switch ($shape->getType()) {
            case 'string':
            case 'blob':
                return var_export('change me', true);
            case 'integer':
            case 'long':
            case 'double':
            case 'float':
                return var_export(1337, true);
            case 'timestamp':
                return 'new \DateTimeImmutable()';
            case 'boolean':
                return 'false';
        }

        throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $shape->getType()));
    }

    private function generateClient(Operation $operation): void
    {
        $clientName = $this->namespaceRegistry->getClient($operation->getService());
        $clientTestName = $this->namespaceRegistry->getClientTest($operation->getService());
        $methodName = 'test' . $operation->getMethodName();

        try {
            $classBuilder = $this->classRegistry->register($clientTestName->getFqdn(), true);
            if ($classBuilder->hasMethod($methodName)) {
                $this->classRegistry->unregister($clientTestName->getFqdn());

                return;
            }
        } catch (\ReflectionException $e) {
            $classBuilder = $this->createTestClass($clientTestName, $clientName);
        }

        if (null === $operation->getOutput()) {
            $output = ClassName::create('AsyncAws\\Core', 'Result');
        } else {
            $output = $this->namespaceRegistry->getResult($operation->getOutput());
        }
        $classBuilder->setExtends(TestCase::class);
        $classBuilder->addUse(TestCase::class);
        $classBuilder->addUse(MockHttpClient::class);
        $classBuilder->addUse(NullProvider::class);
        $classBuilder->addUse($output->getFqdn());
        $classBuilder->addUse($clientName->getFqdn());

        $classBuilder->addMethod($methodName)
            ->setReturnType('void')
            ->setBody(strtr('
                $client = new CLASS_NAME([], new NullProvider(), new MockHttpClient());

                $input = INPUT_CONSTRUCTOR;
                $result = $client->METHOD($input);

                self::assertInstanceOf(RESULT::class, $result);
                self::assertFalse($result->info()[\'resolved\']);

            ', [
                'CLASS_NAME' => $clientName->getName(),
                'INPUT_CONSTRUCTOR' => $this->getInputCode($classBuilder, $operation->getInput(), false),
                'RESULT' => $output->getName(),
                'METHOD' => lcfirst($operation->getMethodName()),
            ]));
    }

    private function getResultAssert(StructureShape $shape): string
    {
        return implode("\n", array_map(function (StructureMember $member) {
            $getterMethodName = 'get' . ucfirst(GeneratorHelper::normalizeName($member->getName()));

            switch ($member->getShape()->getType()) {
                case 'string':
                    return \sprintf('self::assertSame("changeIt", $result->%s());', $getterMethodName);
                case 'boolean':
                    return \sprintf('self::assertFalse($result->%s());', $getterMethodName);
                case 'integer':
                case 'long':
                    return \sprintf('self::assertSame(1337, $result->%s());', $getterMethodName);
                default:
                    return \sprintf('// self::assertTODO(expected, $result->%s());', $getterMethodName);
            }
        }, $shape->getMembers()));
    }

    private function createTestClass(ClassName $testClassName, ClassName $sourceClassName): ClassBuilder
    {
        $classBuilder = $this->classRegistry->register($testClassName->getFqdn());
        $classBuilder->addUse($sourceClassName->getFqdn());
        $classBuilder->addUse(TestCase::class);

        $classBuilder->setExtends(TestCase::class);

        return $classBuilder;
    }

    private function createClientTestClass(ClassName $testClassName, Operation $operation): ClassBuilder
    {
        $clientClassName = $this->namespaceRegistry->getClient($operation->getService());

        $classBuilder = $this->classRegistry->register($testClassName->getFqdn());
        $classBuilder->addUse(TestCase::class);
        $classBuilder->addUse($clientClassName->getFqdn());
        $classBuilder->addUse(NullProvider::class);

        $classBuilder->setExtends(TestCase::class);
        $classBuilder->addMethod('getClient')
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

        return $classBuilder;
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
                        $f($element->addChild('member'), $value);
                    }
                } else {
                    if (!is_numeric($key)) {
                        $element->addChild($key, (string) $value);
                    } else {
                        $element->addChild('member', $value);
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
if (!class_exists(PHPUnitTestCase::class)) {
    eval('namespace PHPUnit\\Framework; class TestCase {}');
}
