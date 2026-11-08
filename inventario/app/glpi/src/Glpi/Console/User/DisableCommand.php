<?php

namespace Glpi\Console\User;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class DisableCommand extends AbstractUserCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:disable');
        $this->setDescription(__('Disable a GLPI user'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $user = new User();
        if ($user->getFromDBbyName($username)) {
            $user->update([
                'id' => $user->getID(),
                'is_active' => 0,
            ]);
            $output->writeln('<info>' . __('User disabled') . '</info>');
            return 0;
        } else {
            $output->writeln('<error>' . __('User not found') . '</error>');
            return 1;
        }
    }
}
