<?php
/**
 * Created by PhpStorm.
 * User: Soporte
 * Date: 23/10/2018
 * Time: 15:47
 */

namespace App\commands;

use Greenter\Ubl\Resolver\UblVersionResolver;
use Greenter\Ubl\SchemaValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ValidateCommand
 */
class ValidateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('validate')
            ->setDescription('Schema Validator UBL 2.0, 2.1')
            ->setHelp('This command allows you to validate XML files.')
        ;

        $this
            ->addArgument('file', InputArgument::REQUIRED, 'XML Path')
            ->addArgument('version', InputArgument::OPTIONAL, 'UBL Version.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $version = $input->getArgument('version');
        if (!file_exists($file)) {
            $output->writeln("<error>File $file not found.</error>");
            return;
        }
        $xml = file_get_contents($file);

        if (empty($version) && empty($version = $this->getVersion($xml))) {
            $output->writeln('<error>UBL Version not found.</error>');
            return;
        }

        $validator = new SchemaValidator();
        $validator->setVersion($version);

        $result = $validator->validate($xml);

        if ($result) {
            $output->writeln('<info>SUCCESS!!!</info>');
        } else {
            $output->writeln("<error>{$validator->getMessage()}</error>");
        }
    }

    private function getVersion($xml)
    {
        $resolver = new UblVersionResolver();

        return $resolver->getVersion($xml);
    }
}