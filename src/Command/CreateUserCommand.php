<?php

namespace App\Command;

use App\Entity\User;
use App\Servers\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('username', InputArgument::REQUIRED, 'This is the username')
            ->addArgument('password', InputArgument::OPTIONAL, 'The Password of user')
            ->addArgument('roles', InputArgument::OPTIONAL, 'This is the user roles')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');
        $this->userManager->createUser($username,$password,$roles);


        if ($username) {
            $io->note(sprintf('You passed an argument: %s', $username));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have create a user now');
    }
}
