<?php

namespace Glpi\Console\User;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class EnableCommand extends AbstractUserCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:enable');
        $this->setDescription(__('Enable a GLPI user'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $user = new User();
        if ($user->getFromDBbyName($username)) {
            $user->update([
                'id' => $user->getID(),
                'is_active' => 1,
            ]);
            $output->writeln('<info>' . __('User enabled') . '</info>');
            return 0;
        } else {
            $output->writeln('<error>' . __('User not found') . '</error>');
            return 1;
        }
    }
}
