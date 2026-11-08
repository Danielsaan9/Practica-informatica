<?php

namespace Glpi\Console\Security;

use Glpi\Console\AbstractCommand;
use Glpi\Security\SessionTracker;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use User;

class SessionListCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('security:session:list');
        $this->setDescription(__('List active sessions'));
        $this->addOption('login', 'u', InputOption::VALUE_OPTIONAL, __('Username'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getOption('login');
        $user = new User();

        if ($username && !$user->getFromDBbyName($username)) {
            $output->writeln("<error>" . sprintf(__("User %s not found"), $username) . "</error>");
            return 1;
        }

        $session_tracker = new SessionTracker();

        $sessions = $session_tracker->getSessions($user->getID(), [
            'status' => 'active',
        ]);

        $table = new Table($output);
        $table->setHeaders([
            __('ID'),
            _n('Type', 'Types', 1),
            _n('User', 'Users', 1),
            __('Details'),
            _n('IP address', 'IP addresses', 1),
            __('Login'),
            __('Last activity'),
        ]);
        foreach ($sessions as $session) {
            $table->addRow([
                $session['internal_identifier'],
                $session['type_raw'],
                $session['user'],
                html_entity_decode(strip_tags($session['details'])),
                $session['ip_address'],
                $session['login'],
                $session['last_activity'],
            ]);
        }

        $table->render();
        return 0;
    }
}
