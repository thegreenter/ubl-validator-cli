<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 23/10/2018
 * Time: 15:47
 */

namespace App\commands;

use Greenter\Ubl\Resolver\{
    UblPathResolver,
    UblVersionResolver
};
use Greenter\Ubl\UblValidator;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface
};

/**
 * Class ValidateCommand
 */
class ValidateCommand extends Command
{
    /**
     * @var UblPathResolver
     */
    private $pathResolver;

    /**
     * ValidateCommand constructor.
     */
    public function __construct()
    {
        $this->pathResolver = new UblPathResolver();

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('validate')
            ->setDescription('Schema Validator UBL 2.0, 2.1')
            ->setHelp('This command allows you to validate XML files.')
        ;

        $this
            ->addArgument('file', InputArgument::REQUIRED, 'XML Path')
            ->addArgument('version', InputArgument::OPTIONAL, 'UBL Version')
            ->addArgument('directory', InputArgument::OPTIONAL, 'XSD Directory')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $version = $input->getArgument('version');
        $directory = $input->getArgument('directory');

        if (!file_exists($file)) {
            $output->writeln("<error>File $file not found.</error>");
            return;
        }
        $doc = $this->getDocument($file);

        if (empty($version) && empty($version = $this->getVersion($doc))) {
            $output->writeln('<error>UBL Version not found.</error>');
            return;
        }

        if (!empty($directory)) {
            $this->pathResolver->baseDirectory = $directory;
        }

        $this->pathResolver->version = $version;
        $output->writeln("<info>UBL Version: $version</info>");

        $validator = new UblValidator();
        $validator->pathResolver = $this->pathResolver;

        if ($validator->isValid($doc)) {
            $output->writeln('<fg=black;bg=green>SUCCESS!!!</>');
        } else {
            $output->writeln("<error>{$validator->getError()}</error>");
        }
    }

    private function getVersion(\DOMDocument $doc)
    {
        $resolver = new UblVersionResolver();

        return $resolver->getVersion($doc);
    }

    private function getDocument($file)
    {
        $doc = new \DOMDocument();
        $doc->load($file);

        return $doc;
    }
}