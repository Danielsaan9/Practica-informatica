<?php

use Glpi\Application\View\TemplateRenderer;

/**
 * Dialup instantiation of NetworkPort. A dialup connection (also known a point-to-point protocol) allows connection between to sites through specific connections.
 * @since 0.84
 */
class NetworkPortDialup extends NetworkPortInstantiation
{
    public static function getTypeName($nb = 0)
    {
        return __('Connection by dial line - Dialup Port');
    }

    public function showInstantiationForm(NetworkPort $netport, $options, $recursiveItems)
    {
        $twig_params = [
            'item' => $this,
            'netport' => $netport,
            'params' => $options,
            'connection_label' => __('Connected to'),
        ];
        // language=Twig
        echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {% do call([item, 'showMacField'], [netport, params]) %}
            {% set connection_field %}
                {% do call([item, 'showConnection'], [netport, true]) %}
            {% endset %}
            {{ fields.htmlField('', connection_field, connection_label) }}
TWIG, $twig_params);
    }
}
