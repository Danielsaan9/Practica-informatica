<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\AnswersSet;
use Glpi\Form\Destination\AbstractCommonITILFormDestination;
use Glpi\Form\Destination\AbstractConfigField;
use Glpi\Form\Destination\FormDestination;
use Glpi\Form\Export\Context\DatabaseMapper;
use Glpi\Form\Export\Serializer\DynamicExportDataField;
use Glpi\Form\Export\Specification\DataRequirementSpecification;
use Glpi\Form\Form;
use InvalidArgumentException;
use ITILFollowupTemplate;
use Override;
use Session;

final class ITILFollowupField extends AbstractConfigField
{
    #[Override]
    public function getLabel(): string
    {
        return _n('Followup', 'Followups', Session::getPluralNumber());
    }

    #[Override]
    public function getConfigClass(): string
    {
        return ITILFollowupFieldConfig::class;
    }

    #[Override]
    public function renderConfigForm(
        Form $form,
        FormDestination $destination,
        JsonFieldInterface $config,
        string $input_name,
        array $display_options
    ): string {
        if (!$config instanceof ITILFollowupFieldConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        $twig = TemplateRenderer::getInstance();
        return $twig->render('pages/admin/form/itil_config_fields/itilfollowuptemplate.html.twig', [
            // Possible configuration constant that will be used to to hide/show additional fields
            'CONFIG_SPECIFIC_VALUES'  => ITILFollowupFieldStrategy::SPECIFIC_VALUES->value,

            // General display options
            'options' => $display_options,

            // Specific additional config for SPECIFIC_VALUES strategy
            'specific_value_extra_field' => [
                'aria_label'     => __("Select followup templates..."),
                'value'           => $config->getSpecificITILFollowupTemplatesIds() ?? [],
                'input_name'      => $input_name . "[" . ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS . "]",
            ],
        ]);
    }

    #[Override]
    public function applyConfiguratedValueToInputUsingAnswers(
        JsonFieldInterface $config,
        array $input,
        AnswersSet $answers_set
    ): array {
        if (!$config instanceof ITILFollowupFieldConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        // Only one strategy is allowed
        $strategy = current($config->getStrategies());

        // Compute value according to strategy
        $itilfollowuptemplates_ids = $strategy->getITILFollowupTemplatesIDs($config);

        if (!empty($itilfollowuptemplates_ids)) {
            $input['_itilfollowuptemplates_id'] = $itilfollowuptemplates_ids;
        }

        return $input;
    }

    #[Override]
    public function getDefaultConfig(Form $form): ITILFollowupFieldConfig
    {
        return new ITILFollowupFieldConfig(
            ITILFollowupFieldStrategy::NO_FOLLOWUP,
        );
    }

    public function getStrategiesForDropdown(): array
    {
        $values = [];
        foreach (ITILFollowupFieldStrategy::cases() as $strategies) {
            $values[$strategies->value] = $strategies->getLabel();
        }
        return $values;
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }

    #[Override]
    public function prepareInput(array $input): array
    {
        $input = parent::prepareInput($input);

        // Ensure that itilfollowuptemplate_ids is an array
        if (!is_array($input[$this->getKey()][ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS] ?? null)) {
            unset($input[$this->getKey()][ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS]);
        }

        return $input;
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::TIMELINE;
    }

    #[Override]
    public function exportDynamicConfig(
        array $config,
        AbstractCommonITILFormDestination $destination,
    ): DynamicExportDataField {
        $fallback = parent::exportDynamicConfig($config, $destination);
        $requirements = [];

        // Check if templates are defined
        $template_ids = $config[ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS] ?? null;
        if ($template_ids === null) {
            return $fallback;
        }

        foreach ($template_ids as $i => $template_id) {
            $template = ITILFollowupTemplate::getById($template_id);
            if ($template) {
                // Insert template name and requirement
                $requirement = DataRequirementSpecification::fromItem($template);
                $requirements[] = $requirement;
                $config[ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS][$i] = $requirement->name;
            }
        }

        return new DynamicExportDataField($config, $requirements);
    }

    #[Override]
    public static function prepareDynamicConfigDataForImport(
        array $config,
        AbstractCommonITILFormDestination $destination,
        DatabaseMapper $mapper,
    ): array {
        // Check if templates are defined
        $template_names = $config[ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS] ?? null;
        if ($template_names === null) {
            return parent::prepareDynamicConfigDataForImport(
                $config,
                $destination,
                $mapper,
            );
        }

        // Insert ids
        foreach ($template_names as $i => $template_name) {
            $id = $mapper->getItemId(ITILFollowupTemplate::class, $template_name);
            $config[ITILFollowupFieldConfig::ITILFOLLOWUPTEMPLATE_IDS][$i] = $id;
        }

        return $config;
    }
}
