<?php

use Glpi\Application\View\TemplateRenderer;

/**
 * Map geolocation
 **/
trait MapGeolocation
{
    /**
     * get openstreetmap
     *
     * @return void
     */
    public function showMap()
    {
        // language=Twig
        echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% set rand = random() %}
            <div id="setlocation_container_{{ rand }}"></div>
            <script type="module">
                import('/js/modules/Form/GeolocationField.js').then((m) => {
                    new m.default('setlocation_container_{{ rand }}');
                });
            </script>
TWIG);
    }
}
