<?php

namespace Glpi\Inventory;

use ArrayAccess;
use CommonDBTM;
use LogicException;
use Toolbox;

/**
 * Result of the import of a single inventory file.
 *
 * @implements ArrayAccess<string, mixed> for legacy compat
 */
final readonly class ImportResult implements ArrayAccess
{
    /**
     * @param CommonDBTM[] $items
     */
    public function __construct(
        private string $filename,
        private bool $success,
        private ?string $message,
        private array $items = [],
        private ?Request $request = null,
    ) {}

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return CommonDBTM[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * ===== LEGACY ARRAY ACCESS =====
     * @deprecated 12.0.0
     */

    /**
     * Legacy array access.
     * @deprecated 12.0.0 Use the dedicated getters instead.
     */
    public function offsetExists(mixed $offset): bool
    {
        Toolbox::deprecated('ImportResult array access is deprecated. Use the dedicated getters instead.');
        return in_array($offset, ['success', 'message', 'items', 'request', 'filename'], true);
    }

    /**
     * Legacy array access.
     * @deprecated 12.0.0 Use the dedicated getters instead.
     */
    public function offsetGet(mixed $offset): mixed
    {
        Toolbox::deprecated('ImportResult array access is deprecated. Use the dedicated getters instead.');
        return match ($offset) {
            'filename' => $this->filename,
            'success'  => $this->success,
            'message'  => $this->message,
            'items'    => $this->items,
            'request'  => $this->request,
            default    => null,
        };
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new LogicException('ImportResult is read-only.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new LogicException('ImportResult is read-only.');
    }

    /**
     * ===== /LEGACY ARRAY ACCESS =====
     */
}
