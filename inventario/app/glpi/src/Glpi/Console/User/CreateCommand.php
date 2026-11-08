<?php

namespace Glpi\Console\User;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class CreateCommand extends AbstractUserCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:create');
        $this->setDescription(__('Create a new local GLPI user'));

        $this->addOption('password', 'p', InputOption::VALUE_OPTIONAL, __('Password'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user_input = ['name' => $input->getArgument('username')];

        $user = new User();
        if ($user->getFromDBbyName($user_input['name'])) {
            $output->writeln('<error>' . __('User already exists') . '</error>');
            return 1;
        }

        $password = $this->askForPassword($input, $output);
        if ($password === false) {
            return 1;
        }
        $user_input['password'] = $password;
        $user_input['password2'] = $password;

        if ($user->add($user_input)) {
            $output->writeln('<info>' . __('User created') . '</info>');
            return 0;
        } else {
            $output->writeln('<error>' . __('Failed to create user') . '</error>');
            return 1;
        }
    }
}
