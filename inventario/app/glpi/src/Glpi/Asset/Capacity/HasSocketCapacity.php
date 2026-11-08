<?php

namespace Glpi\Asset\Capacity;

use Cable;
use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Glpi\Socket;
use Override;
use Session;

class HasSocketCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Socket::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Socket::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Manage sockets and cable links");
    }

    public function getCloneRelations(): array
    {
        return [
            Socket::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Socket::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s sockets attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Socket::class),
            $this->countAssetsLinkedToPeerItem($classname, Socket::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('socket_types', $classname);

        CommonGLPI::registerStandardTab(
            $classname,
            Socket::class,
            50
        );
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        global $DB;

        $this->unregisterFromTypeConfig('socket_types', $classname);

        $socket = new Socket();
        $socket->deleteByCriteria(
            [
                'itemtype' => $classname,
            ],
            force: true,
            history: false
        );

        // Clean cable data by unlinking the custom asset side
        $it = $DB->request(
            [
                'SELECT' => ['id', 'itemtype_endpoint_a', 'itemtype_endpoint_b'],
                'FROM'   => Cable::getTable(),
                'WHERE'  => [
                    'OR' => [
                        'itemtype_endpoint_a' => $classname,
                        'itemtype_endpoint_b' => $classname,
                    ],
                ],
            ]
        );
        $cable = new Cable();
        foreach ($it as $data) {
            if ($data['itemtype_endpoint_a'] === $classname) {
                $cable->update(
                    [
                        'id' => $data['id'],
                        'itemtype_endpoint_a' => null,
                        'items_id_endpoint_a' => 0,
                        'socketmodels_id_endpoint_a' => 0,
                        'sockets_id_endpoint_a' => 0,
                    ],
                    history: false
                );
            } else {
                $cable->update(
                    [
                        'id' => $data['id'],
                        'itemtype_endpoint_b' => null,
                        'items_id_endpoint_b' => 0,
                        'socketmodels_id_endpoint_b' => 0,
                        'sockets_id_endpoint_b' => 0,
                    ],
                    history: false
                );
            }
        }

        $this->deleteRelationLogs($classname, Socket::class);
        $this->deleteDisplayPreferences($classname, Socket::rawSearchOptionsToAdd());
    }
}
