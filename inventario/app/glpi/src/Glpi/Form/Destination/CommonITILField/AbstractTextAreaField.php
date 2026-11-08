<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\AnswersSet;
use Glpi\Form\Destination\AbstractConfigField;
use Glpi\Form\Destination\FormDestination;
use Glpi\Form\Form;
use Glpi\Form\Migration\DestinationFieldConverterInterface;
use Glpi\Form\Migration\FormMigration;
use Glpi\Form\Migration\TagConversionTrait;
use Glpi\Form\Tag\FormTagsManager;
use InvalidArgumentException;
use Override;

/**
 * Make sure to add the #[HasFormTags] attribute to child classes.
 * This attribute is required for cloning to work as expected, and since PHP
 * attributtes are not inherited we can't define it here.
 */
abstract class AbstractTextAreaField extends AbstractConfigField implements DestinationFieldConverterInterface
{
    use TagConversionTrait;

    abstract protected function getColumnName(): string;

    #[Override]
    public function getConfigClass(): string
    {
        return SimpleValueConfig::class;
    }

    #[Override]
    public function getDefaultConfig(Form $form): SimpleValueConfig
    {
        return new SimpleValueConfig("");
    }

    #[Override]
    public function applyConfiguratedValueToInputUsingAnswers(
        JsonFieldInterface $config,
        array $input,
        AnswersSet $answers_set
    ): array {
        if (!$config instanceof SimpleValueConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        $tag_manager = new FormTagsManager();
        $input[$this->getColumnName()] = $tag_manager->insertTagsContent(
            $config->getValue(),
            $answers_set
        );

        return $input;
    }

    #[Override]
    public function renderConfigForm(
        Form $form,
        FormDestination $destination,
        JsonFieldInterface $config,
        string $input_name,
        array $display_options
    ): string {
        if (!$config instanceof SimpleValueConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        $template = <<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}

            {{ fields.textareaField(
                input_name,
                value,
                '',
                options|merge({
                    'field_class'      : '',
                    'no_label'         : true,
                    'enable_richtext'  : true,
                    'enable_images'    : false,
                    'enable_form_tags' : true,
                    'form_tags_form_id': form_id,
                    'content_style'    : 'body { line-height: 2.3; }',
                    'mb'               : '',
                })
            ) }}
TWIG;

        $tag_manager = new FormTagsManager();
        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'form_id'    => $form->fields['id'],
            'value'      => $tag_manager->refreshTagsContent($config->getValue()),
            'input_name' => $input_name . "[" . SimpleValueConfig::VALUE . "]",
            'options'    => $display_options,
        ]);
    }

    #[Override]
    public function convertFieldConfig(
        FormMigration $migration,
        Form $form,
        array $rawData
    ): JsonFieldInterface {
        $key = $this->getColumnName();
        if (isset($rawData[$key])) {
            $title = $this->convertLegacyTags($rawData[$key], $migration);
            return new SimpleValueConfig($title);
        }

        return $this->getDefaultConfig($form);
    }
}
