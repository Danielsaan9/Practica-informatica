<?php

namespace Glpi\Form\Migration;

use Glpi\Form\QuestionType\QuestionTypeCheckbox;
use Glpi\Form\QuestionType\QuestionTypeDateTime;
use Glpi\Form\QuestionType\QuestionTypeDropdown;
use Glpi\Form\QuestionType\QuestionTypeEmail;
use Glpi\Form\QuestionType\QuestionTypeFile;
use Glpi\Form\QuestionType\QuestionTypeItem;
use Glpi\Form\QuestionType\QuestionTypeItemDropdown;
use Glpi\Form\QuestionType\QuestionTypeLongText;
use Glpi\Form\QuestionType\QuestionTypeNumber;
use Glpi\Form\QuestionType\QuestionTypeRadio;
use Glpi\Form\QuestionType\QuestionTypeRequester;
use Glpi\Form\QuestionType\QuestionTypeRequestType;
use Glpi\Form\QuestionType\QuestionTypeShortText;
use Glpi\Form\QuestionType\QuestionTypeUrgency;
use Glpi\Toolbox\SingletonTrait;
use InvalidArgumentException;

final class TypesConversionMapper
{
    use SingletonTrait;

    /** @var array<string, ?FormQuestionDataConverterInterface> */
    private array $questions_types_conversion_map;

    private function __construct()
    {
        $this->questions_types_conversion_map = $this->getDefaultQuestionTypesConverter();
    }

    /**
     * Retrieve the map of types to convert
     *
     * @return array
     */
    public function getQuestionTypesConversionMap(): array
    {
        return $this->questions_types_conversion_map;
    }

    public function registerPluginQuestionTypeConverter(
        string $formcreator_type,
        ?FormQuestionDataConverterInterface $converter
    ): void {
        if (!array_key_exists($formcreator_type, $this->questions_types_conversion_map)) {
            throw new InvalidArgumentException("Unknown type: `$formcreator_type`");
        }

        $this->questions_types_conversion_map[$formcreator_type] = $converter;
    }

    public function getPluginHintForType(string $type): ?string
    {
        return match ($type) {
            default      => null,
            "hidden"     => "advancedforms",
            "ldapselect" => "advancedforms",
            "ip"         => "advancedforms",
            "hostname"   => "advancedforms",
            "tag"        => "tag",
            "fields"     => "fields",
        };
    }

    /** @return array<string, ?FormQuestionDataConverterInterface> */
    private function getDefaultQuestionTypesConverter(): array
    {
        return [
            'checkboxes'  => new QuestionTypeCheckbox(),
            'date'        => new QuestionTypeDateTime(),
            'datetime'    => new QuestionTypeDateTime(),
            'dropdown'    => new QuestionTypeItemDropdown(),
            'email'       => new QuestionTypeEmail(),
            'file'        => new QuestionTypeFile(),
            'float'       => new QuestionTypeNumber(),
            'glpiselect'  => new QuestionTypeItem(),
            'integer'     => new QuestionTypeNumber(),
            'multiselect' => new QuestionTypeDropdown(),
            'radios'      => new QuestionTypeRadio(),
            'requesttype' => new QuestionTypeRequestType(),
            'select'      => new QuestionTypeDropdown(),
            'textarea'    => new QuestionTypeLongText(),
            'text'        => new QuestionTypeShortText(),
            'time'        => new QuestionTypeDateTime(),
            'urgency'     => new QuestionTypeUrgency(),

            // We do not have a question of type "Actor", we have more specific
            // types: "Assignee", "Requester" and "Observer".
            // Fallback to "Requester" as we can't guess the expected type.
            'actor'       => new QuestionTypeRequester(),

            // Description is replaced by a new block : Comment
            'description' => null,

            // Unsupported types, some of them might be implemented by plugins.
            'fields'      => null,
            'tag'         => null,
            'hidden'      => null,
            'hostname'    => null,
            'ip'          => null,
            'ldapselect'  => null,

            // Invalid type
            'undefined'   => null,
        ];
    }
}
