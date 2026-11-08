<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\AnswersSet;
use Glpi\Form\Destination\AbstractConfigField;
use Glpi\Form\Destination\FormDestination;
use Glpi\Form\Form;
use InvalidArgumentException;
use Override;
use RequestType;

final class RequestSourceField extends AbstractConfigField
{
    #[Override]
    public function getLabel(): string
    {
        return RequestType::getTypeName(1);
    }

    #[Override]
    public function getConfigClass(): string
    {
        return RequestSourceFieldConfig::class;
    }

    #[Override]
    public function renderConfigForm(
        Form $form,
        FormDestination $destination,
        JsonFieldInterface $config,
        string $input_name,
        array $display_options
    ): string {
        if (!$config instanceof RequestSourceFieldConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        $twig = TemplateRenderer::getInstance();
        return $twig->render('pages/admin/form/itil_config_fields/request_source.html.twig', [
            // Possible configuration constant that will be used to to hide/show additional fields
            'CONFIG_SPECIFIC_VALUE'  => RequestSourceFieldStrategy::SPECIFIC_VALUE->value,

            // General display options
            'options' => $display_options,

            // Specific additional config for SPECIFIC_VALUE strategy
            'specific_value_extra_field' => [
                'empty_label'     => __("Select a request source..."),
                'value'           => $config->getSpecificRequestSource(),
                'input_name'      => $input_name . "[" . RequestSourceFieldConfig::REQUEST_SOURCE . "]",
                'itemtype'        => RequestType::class,
            ],
        ]);
    }

    #[Override]
    public function applyConfiguratedValueToInputUsingAnswers(
        JsonFieldInterface $config,
        array $input,
        AnswersSet $answers_set
    ): array {
        global $DB;

        if (!$config instanceof RequestSourceFieldConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        // Only one strategy is allowed
        $strategy = current($config->getStrategies());

        // Compute value according to strategy
        $request_source = $strategy->getRequestSource($config, $answers_set);

        // Do not edit input if invalid value was found
        $db_values = $DB->request(['FROM' => 'glpi_requesttypes', 'WHERE' => ['is_active' => 1]]);
        $valid_values = [];
        foreach ($db_values as $data) {
            $valid_values[] = $data['id'];
        }

        if (!in_array($request_source, $valid_values)) {
            return $input;
        }

        // Apply value
        $input['requesttypes_id'] = $request_source;
        return $input;
    }

    #[Override]
    public function getDefaultConfig(Form $form): RequestSourceFieldConfig
    {
        return new RequestSourceFieldConfig(
            RequestSourceFieldStrategy::FROM_TEMPLATE
        );
    }

    public function getStrategiesForDropdown(): array
    {
        $values = [];
        foreach (RequestSourceFieldStrategy::cases() as $strategies) {
            $values[$strategies->value] = $strategies->getLabel();
        }
        return $values;
    }

    #[Override]
    public function getWeight(): int
    {
        return 60;
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::PROPERTIES;
    }
}
