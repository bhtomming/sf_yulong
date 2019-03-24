<?php

namespace App\Command;

use App\Servers\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangePasswordCommand extends Command
{
    protected static $defaultName = 'app:user:update';
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('This Command will change user password')
            ->addArgument('name', InputArgument::OPTIONAL, 'User name')
            ->addArgument('password', InputArgument::OPTIONAL, 'password of User')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        $this->userManager->changePassword($name,$password);
        if ($name) {
            $io->note(sprintf('You change the password of : %s', $name));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
