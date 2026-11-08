<?php

namespace Glpi\Console\User;

use Auth;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class ResetPasswordCommand extends AbstractUserCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:reset_password');
        $this->setDescription(__('Reset the password of a local GLPI user'));

        $this->addOption('password', 'p', InputOption::VALUE_OPTIONAL, __('Password'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user_input = ['name' => $input->getArgument('username')];

        $user = new User();
        if (!$user->getFromDBbyName($user_input['name'])) {
            $output->writeln('<error>' . __('User not found') . '</error>');
            return 1;
        }

        if ($user->fields['authtype'] !== Auth::DB_GLPI) {
            $output->writeln('<error>' . __("The authentication method configuration doesn't allow you to change your password.") . '</error>');
            return 1;
        }

        $user_input['id'] = $user->getID();

        $password = $this->askForPassword($input, $output);
        if ($password === false) {
            return 1;
        }
        $user_input['password'] = $password;
        $user_input['password2'] = $password;

        if (\Session::callAsSystem(fn() => $user->update($user_input))) {
            $output->writeln('<info>' . __('Reset password successful.') . '</info>');
            return 0;
        } else {
            $output->writeln('<error>' . __('Unable to reset password, please contact your administrator') . '</error>');
            return 1;
        }
    }
}
