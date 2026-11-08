<?php

namespace Glpi\System\Requirement;

use Toolbox;

/**
 * @since 9.5.0
 */
class MemoryLimit extends AbstractRequirement
{
    /**
     * Minimal allocated memory size.
     *
     */
    private int $min;

    /**
     * @param int $min  Minimal allocated memory.
     */
    public function __construct(int $min)
    {
        parent::__construct(
            __('Allocated memory')
        );

        $this->min = $min;
    }

    protected function check()
    {
        $limit = Toolbox::getMemoryLimit();

        /*
         * $limit can be:
         *  -1 : unlimited
         *  >0 : allocated bytes
         */
        if ($limit == -1 || $limit >= $this->min) {
            $this->validated = true;
            $this->validation_messages[] = $limit > 0
            ? sprintf(__('Allocated memory is sufficient.'), Toolbox::getSize($this->min))
            : __('Allocated memory is unlimited.');
        } else {
            $this->validated = false;
            $this->validation_messages[] = sprintf(__('%1$s: %2$s'), __('Allocated memory'), Toolbox::getSize($limit));
            $this->validation_messages[] = sprintf(__('A minimum of %s is commonly required for GLPI.'), Toolbox::getSize($this->min));
            $this->validation_messages[] = __('Try increasing the memory_limit parameter in the php.ini file.');
        }
    }
}
