<?php

namespace Glpi\Console\User;

use Glpi\Console\AbstractCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractUserCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this->addArgument('username', InputArgument::REQUIRED, __('Login'));
    }

    protected function askForPassword(InputInterface $input, OutputInterface $output): string|false
    {
        $supplied_password = $input->getOption('password');
        if ($supplied_password !== null) {
            return $supplied_password;
        }

        // Ask for password and then confirm it
        $helper = new QuestionHelper();
        $question = new Question(__('Enter password'));
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);
        $question = new Question(__('Confirm password'));
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password2 = $helper->ask($input, $output, $question);
        if ($password !== $password2) {
            $output->writeln('<error>' . __('Passwords do not match') . '</error>');
            return false;
        }

        return $password;
    }
}
