<?php

namespace Glpi\Form\Export\Specification;

use Glpi\Form\Export\Serializer\DynamicExportData;

final class FormContentSpecification
{
    public int $id;
    public string $uuid;
    public string $name;
    public ?string $header = null;
    public ?string $description = null;
    public string $category_name;
    public string $entity_name;
    public bool $is_recursive;
    public bool $is_active;
    public string $render_layout;
    public string $submit_button_visibility_strategy;

    /** @var string|CustomIllustrationContentSpecification $illustration**/
    public string|CustomIllustrationContentSpecification $illustration;

    /** @var ConditionDataSpecification[] $conditions */
    public array $submit_button_conditions;

    /** @var SectionContentSpecification[] $sections */
    public array $sections = [];

    /** @var CommentContentSpecification[] $comments */
    public array $comments = [];

    /** @var QuestionContentSpecification[] $questions */
    public array $questions = [];

    /** @var AccesControlPolicyContentSpecification[] $policies */
    public array $policies = [];

    /** @var DestinationContentSpecification[] $destinations */
    public array $destinations = [];

    /** @var TranslationContentSpecification[] $translations */
    public array $translations = [];

    /** @var DataRequirementSpecification[] $data_requirements */
    public array $data_requirements = [];

    /** @var CustomTypeRequirementSpecification[] $data_requirements */
    public array $custom_types_requirements = [];

    /** @var PluginRequirementSpecification[] $plugin_requirements */
    public array $plugin_requirements = [];

    /** @return DataRequirementSpecification[] */
    public function getDataRequirements(): array
    {
        return $this->data_requirements;
    }
    public function addDataRequirement(
        DataRequirementSpecification $requirement
    ): void {
        $this->data_requirements[] = $requirement;
    }

    public function addRequirementsFromDynamicData(DynamicExportData $data): void
    {
        array_push($this->data_requirements, ...$data->getRequirements());
    }

    public function getCustomTypesRequirements(): array
    {
        return $this->custom_types_requirements;
    }
    public function addCustomTypeRequirement(
        CustomTypeRequirementSpecification $requirement
    ): void {
        $this->custom_types_requirements[] = $requirement;
    }

    public function getPluginsRequirements(): array
    {
        return $this->plugin_requirements;
    }
    public function addPluginRequirement(
        PluginRequirementSpecification $requirement
    ): void {
        $requirements = array_map(
            fn(PluginRequirementSpecification $r): string => $r->key,
            $this->plugin_requirements,
        );

        // Do nothing if requirement already exist
        if (in_array($requirement->key, $requirements)) {
            return;
        }

        $this->plugin_requirements[] = $requirement;
    }
}
