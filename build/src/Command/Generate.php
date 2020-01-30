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
        $operation = $definition['operations'][$input->getArgument('operation')];

        $serviceUcfirst = ucfirst($service);
        $baseNamespace = sprintf('AsyncAws\\%s', $serviceUcfirst);
        $namespace = ClassFactory::fromExistingClass(sprintf('%s\\%sClient', $baseNamespace, $serviceUcfirst));

        $classes = $namespace->getClasses();
        $class = $classes[array_key_first($classes)];
        $class->removeMethod(lcfirst($operation['name']));
        $method = $class->addMethod(lcfirst($operation['name']));
        $method->addComment('@link '.$operation['documentationUrl']);

        $method->addParameter('input')->setType('array');

        $this->createHelperClass($definition['shapes'], $serviceUcfirst, $baseNamespace.'\\Result', $operation['output']['shape']);
        $outputClass = sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);

        $method->setBody(<<<PHP
\$input['Action'] = '{$operation['name']}';
\$response = \$this->getResponse('{$operation['http']['method']}', \$input);
return new \\$outputClass(\$response);
PHP
);

        $printer = new PsrPrinter();
        file_put_contents(sprintf('%s/%s/%sClient.php', $this->srcDirectory, $serviceUcfirst, $serviceUcfirst), "<?php\n\n".$printer->printNamespace($namespace));

        // Update manifest file
        $manifest['services'][$service]['methods'][$input->getArgument('operation')]['generated'] = date('c');
        file_put_contents($this->manifestFile, json_encode($manifest, JSON_PRETTY_PRINT));

        return 0;
    }

    private function createHelperClass(array $shapes, $service, $baseNamespace, $className)
    {
        $namespace = new PhpNamespace($baseNamespace);
        $namespace->addUse(Result::class);
        $class = $namespace->addClass($className);
        $class->addExtend(Result::class);
        $members = $shapes[$className]['members'];
        foreach ($members as $name => $data) {
            $class->addProperty($name)->setPrivate();
            $parameterType = $members[$name]['shape'];

            if (!in_array($shapes[$parameterType]['type'], ['string'])) {
                $this->createHelperClass($shapes, $service, $baseNamespace, $parameterType);
            } else {
                $parameterType = $shapes[$parameterType]['type'];
            }

            $class->addMethod('get'.$name)
                ->setReturnType($parameterType)
                ->setBody(<<<PHP
\$this->initialize();
return \$this->{$name};
PHP
);

            $printer = new PsrPrinter();
            file_put_contents(sprintf('%s/%s/Result/%s.php', $this->srcDirectory, $service, $className), "<?php\n\n".$printer->printNamespace($namespace));
        }
    }
}