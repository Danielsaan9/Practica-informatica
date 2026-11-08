<?php

namespace Glpi\Console\User;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class DeleteCommand extends AbstractUserCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:delete');
        $this->setDescription(__('Delete a GLPI user'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $user = new User();
        if ($user->getFromDBbyName($username)) {
            $user->delete([
                'id' => $user->getID(),
            ]);
            $output->writeln('<info>' . __('User deleted') . '</info>');
            return 0;
        }
        $output->writeln('<error>' . __('User not found') . '</error>');
        return 1;
    }
}
