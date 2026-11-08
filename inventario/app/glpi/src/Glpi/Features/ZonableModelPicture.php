<?php

namespace Glpi\Features;

interface ZonableModelPicture
{
    /**
     * Display the stencil with the zones
     *
     * @return void
     */
    public function displayStencil(): void;

    /**
     * Display the stencil editor
     *
     * @return void
     */
    public function displayStencilEditor(): void;
}
