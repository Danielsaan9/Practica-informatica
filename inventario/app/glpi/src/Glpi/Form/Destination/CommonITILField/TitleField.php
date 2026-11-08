<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\AnswersSet;
use Glpi\Form\Destination\AbstractConfigField;
use Glpi\Form\Destination\FormDestination;
use Glpi\Form\Destination\HasFormTags;
use Glpi\Form\Form;
use Glpi\Form\Migration\DestinationFieldConverterInterface;
use Glpi\Form\Migration\FormMigration;
use Glpi\Form\Migration\TagConversionTrait;
use Glpi\Form\Tag\FormTagProvider;
use Glpi\Form\Tag\FormTagsManager;
use InvalidArgumentException;
use Override;

#[HasFormTags]
final class TitleField extends AbstractConfigField implements DestinationFieldConverterInterface
{
    use TagConversionTrait;

    #[Override]
    public function getLabel(): string
    {
        return __("Title");
    }

    #[Override]
    public function getConfigClass(): string
    {
        return SimpleValueConfig::class;
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
                    'aria_label'       : __('Title'),
                    'enable_richtext'  : true,
                    'enable_images'    : false,
                    'enable_form_tags' : true,
                    'form_tags_form_id': form_id,
                    'toolbar'          : false,
                    'editor_height'    : 0,
                    'statusbar'        : false,
                    'mb'               : '',
                })
            ) }}

            <script>
                tinymce.on('AddEditor', (e) => {
                    if (e.editor.id === '{{ input_name ~ '_' ~ options.rand }}') {
                        e.editor.on('keydown', (e) => {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                            }
                        });
                    }
                });
            </script>
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
    public function applyConfiguratedValueToInputUsingAnswers(
        JsonFieldInterface $config,
        array $input,
        AnswersSet $answers_set
    ): array {
        if (!$config instanceof SimpleValueConfig) {
            throw new InvalidArgumentException("Unexpected config class");
        }

        $tag_manager = new FormTagsManager();

        $title = $tag_manager->insertTagsContent(
            $config->getValue(),
            $answers_set
        );

        $input['name'] = html_entity_decode(strip_tags($title));

        return $input;
    }

    #[Override]
    public function getDefaultConfig(Form $form): SimpleValueConfig
    {
        return new SimpleValueConfig((new FormTagProvider())->getTagForForm($form)->html);
    }

    #[Override]
    public function prepareInput(array $input): array
    {
        if (isset($input[$this->getKey()]) && isset($input[$this->getKey()]['value'])) {
            // Remove HTML tags except span with data-form-tag attribute
            $input[$this->getKey()]['value'] = strip_tags($input[$this->getKey()]['value'], '<span>');
        }

        return $input;
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::PROPERTIES;
    }

    #[Override]
    public function convertFieldConfig(FormMigration $migration, Form $form, array $rawData): JsonFieldInterface
    {
        if (isset($rawData['target_name'])) {
            $title = $this->convertLegacyTags($rawData['target_name'], $migration);
            return new SimpleValueConfig($title);
        }

        return $this->getDefaultConfig($form);
    }
}
