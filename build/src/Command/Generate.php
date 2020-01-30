<?php

declare(strict_types=1);

namespace AsyncAws\Build\Command;

use AsyncAws\Aws\Result;
use AsyncAws\Build\Generator\ClassFactory;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Generate extends Command
{
    protected static $defaultName  = 'generate';

    /** @var string */
    private $manifestFile;

    /** @var string */
    private $srcDirectory;

    public function __construct(string $manifestFile, string $srcDirectory)
    {
        $this->manifestFile = $manifestFile;
        $this->srcDirectory = $srcDirectory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDefinition([
            new InputArgument('service', InputArgument::REQUIRED),
            new InputArgument('operation', InputArgument::REQUIRED),
        ]);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $manifest = json_decode(file_get_contents($this->manifestFile), true);
        if (!isset($manifest['services'][$service = $input->getArgument('service')])) {
            $io->error(sprintf('Service "%s" does not exist in manifest.json', $service));
        }

        $definition = json_decode(file_get_contents($manifest['services'][$service]['source']), true);
        $this->generateOperation($definition, ucfirst($service), $input->getArgument('operation'));

        // Update manifest file
        $manifest['services'][$service]['methods'][$input->getArgument('operation')]['generated'] = date('c');
        file_put_contents($this->manifestFile, json_encode($manifest, JSON_PRETTY_PRINT));

        return 0;
    }

    /**
     * Update the API client with a new function call.
     */
    private function generateOperation($definition, $service, $operationName): void
    {
        $operation = $definition['operations'][$operationName];

        $baseNamespace = sprintf('AsyncAws\\%s', $service);
        $namespace = ClassFactory::fromExistingClass(sprintf('%s\\%sClient', $baseNamespace, $service));

        $classes = $namespace->getClasses();
        $class = $classes[array_key_first($classes)];
        $class->removeMethod(lcfirst($operation['name']));
        $method = $class->addMethod(lcfirst($operation['name']));
        $method->addComment('@link '.$operation['documentationUrl']);

        $method->addParameter('input')->setType('array');

        $this->createOutputClass($definition['shapes'], $service, $baseNamespace.'\\Result', $operation['output']['shape'], true);
        $outputClass = sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);

        $method->setBody(
            <<<PHP
\$input['Action'] = '{$operation['name']}';
\$response = \$this->getResponse('{$operation['http']['method']}', \$input);
return new \\$outputClass(\$response);
PHP
        );

        $printer = new PsrPrinter();
        //$printer->setTypeResolving(false);
        file_put_contents(
            sprintf('%s/%s/%sClient.php', $this->srcDirectory, $service, $service),
            "<?php\n\n".$printer->printNamespace($namespace)
        );
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    private function createOutputClass(array $shapes, $service, $baseNamespace, $className, $root = false)
    {
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass($className);
        if ($root) {
            $namespace->addUse(ResponseInterface::class);
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);
            $class->addMethod('populateFromResponse')
                ->setReturnType('void')
                ->setProtected()
                ->setBody('// TODO implement me')
                ->addParameter('response')->setType(ResponseInterface::class);

        }
        $members = $shapes[$className]['members'];
        foreach ($members as $name => $data) {
            $class->addProperty($name)->setPrivate();
            $parameterType = $members[$name]['shape'];

            if (!in_array($shapes[$parameterType]['type'], ['string'])) {
                $this->createOutputClass($shapes, $service, $baseNamespace, $parameterType);
            } else {
                $parameterType = $shapes[$parameterType]['type'];
            }

            $callInitialize = '';
            if ($root) {
                $callInitialize = <<<PHP
\$this->initialize();
PHP;
            }

            $class->addMethod('get'.$name)
                ->setReturnType($parameterType)
                ->setBody(<<<PHP
$callInitialize
return \$this->{$name};
PHP
);

            $printer = new PsrPrinter();
            file_put_contents(sprintf('%s/%s/Result/%s.php', $this->srcDirectory, $service, $className), "<?php\n\n".$printer->printNamespace($namespace));
        }
    }


}