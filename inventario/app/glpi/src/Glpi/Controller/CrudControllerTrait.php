<?php

namespace Glpi\Controller;

use CommonDBTM;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use RuntimeException;

trait CrudControllerTrait
{
    /**
     * @template T of CommonDBTM
     * @param class-string<T> $class
     * @param array<mixed> $input
     * @return T
     */
    private function add(string $class, array $input): CommonDBTM
    {
        $item = getItemForItemtype($class);
        if (!$item) {
            throw new NotFoundHttpException();
        }

        if (!$item->can(-1, CREATE, $input)) {
            throw new AccessDeniedHttpException();
        }

        if (!$item->add($input)) {
            throw new RuntimeException("Failed to create item");
        }

        return $item;
    }

    /**
     * @template T of CommonDBTM
     * @param class-string<T> $class
     * @return T
     */
    private function read(string $class, int  $id): CommonDBTM
    {
        $item = getItemForItemtype($class);
        if (!$item) {
            throw new NotFoundHttpException();
        }

        if (!$item->can($id, READ)) {
            throw new AccessDeniedHttpException();
        }

        return $item;
    }

    /**
     * @template T of CommonDBTM
     * @param class-string<T> $class
     * @param array<mixed> $input
     * @return T
     */
    private function update(string $class, int $id, array $input): CommonDBTM
    {
        $item = getItemForItemtype($class);
        if (!$item || !$item->getFromDB($id)) {
            throw new NotFoundHttpException();
        }

        $input['id'] = $id;
        if (!$item->can($id, UPDATE, $input)) {
            throw new AccessDeniedHttpException();
        }

        if (!$item->update($input)) {
            throw new RuntimeException("Failed to update item");
        }

        return $item;
    }

    /**
     * @param class-string<CommonDBTM> $class
     */
    private function delete(string $class, int $id): void
    {
        $item = getItemForItemtype($class);
        if (!$item || !$item->getFromDB($id)) {
            throw new NotFoundHttpException();
        }

        $input = ['id' => $id];
        if (!$item->can($id, DELETE, $input)) {
            throw new AccessDeniedHttpException();
        }

        if (!$item->delete($input)) {
            throw new RuntimeException("Failed to delete item");
        }
    }

    private function purge(string $class, int $id): void
    {
        $item = getItemForItemtype($class);
        if (!$item || !$item->getFromDB($id)) {
            throw new NotFoundHttpException();
        }

        $input = ['id' => $id];
        if (!$item->can($id, PURGE, $input)) {
            throw new AccessDeniedHttpException();
        }

        if (!$item->delete($input, force: true)) {
            throw new RuntimeException("Failed to purge item");
        }
    }
}
