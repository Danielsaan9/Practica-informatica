<?php

namespace Glpi\Console\Security;

use Glpi\Console\AbstractCommand;
use Glpi\Security\TOTPManager;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use User;

class DisableTFACommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('security:disable_2fa');
        $this->setDescription(__('Disable 2FA for a user'));
        $this->addArgument('login', InputArgument::REQUIRED, __('Username'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('login');
        $user = new User();
        if (!$user->getFromDBbyName($username)) {
            $output->writeln("<error>" . sprintf(__("User %s not found"), $username) . "</error>");
            return 1;
        }
        $totp_manager = new TOTPManager();
        if (!$totp_manager->is2FAEnabled($user->getID())) {
            $output->writeln("<error>" . __("2FA is not enabled for this user") . "</error>");
            return 0;
        }
        if ($totp_manager->get2FAEnforcement($user->getID())) {
            $output->writeln("<info>" . __("2FA is enforced for this user. They will be required to set it up again the next time they log in.") . "</info>");
        }
        $helper = new QuestionHelper();
        $question = new ConfirmationQuestion(__('Are you sure you want to disable 2FA for this user?'), false);
        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }
        $totp_manager->disable2FAForUser($user->getID());
        $output->writeln("<info>" . sprintf(__("2FA disabled for user %s"), $username) . "</info>");
        return 0;
    }
}
