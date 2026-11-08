<?php

namespace Glpi\Kernel\Listener\ControllerListener;

use Glpi\Http\Firewall;
use Glpi\Http\SessionManager;
use Glpi\Kernel\ListenersPriority;
use Glpi\Security\Attribute\SecurityStrategy;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class FirewallStrategyListener implements EventSubscriberInterface
{
    public function __construct(
        private Firewall $firewall,
        private SessionManager $session_manager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', ListenersPriority::CONTROLLER_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if ($this->session_manager->isResourceStateless($event->getRequest())) {
            // Stateless resources are not protected by the firewall.
            return;
        }

        if (!$event->isMainRequest()) {
            // Apply firewall strategy only on main requests.
            return;
        }

        $strategy = null;

        /** @var SecurityStrategy[] $attributes */
        $attributes = $event->getAttributes(SecurityStrategy::class);
        $number_of_attributes = \count($attributes);
        if ($number_of_attributes > 1) {
            throw new RuntimeException(\sprintf(
                'You can apply only one security strategy per HTTP request. You actually used the "%s" attribute %d times.',
                SecurityStrategy::class,
                $number_of_attributes,
            ));
        } elseif ($number_of_attributes === 1) {
            $strategy = current($attributes)->strategy;
        } elseif ($event->isMainRequest()) {
            $strategy = $this->firewall->computeFallbackStrategy($event->getRequest());
        }

        if ($strategy !== null) {
            $this->firewall->applyStrategy($strategy);
        }
    }
}
