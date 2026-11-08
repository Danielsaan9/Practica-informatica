<?php

namespace Glpi\Form\QuestionType;

use Config;
use Document;
use Glpi\Application\View\TemplateRenderer;
use Glpi\Form\Migration\FormQuestionDataConverterInterface;
use Glpi\Form\Question;
use Override;

final class QuestionTypeFile extends AbstractQuestionType implements FormQuestionDataConverterInterface
{
    #[Override]
    public function prepareEndUserAnswer(Question $question, mixed $answer): mixed
    {
        $form         = $question->getForm();
        $document     = new Document();
        $document_ids = [];
        foreach ($answer as $file) {
            $document_ids[] = $document->add([
                'name'             => sprintf('%s - %s', $form->getName(), $question->getName()),
                'entities_id'      => $form->getEntityID(),
                'is_recursive'     => $form->isRecursive(),
                '_filename'        => [$file],
                '_prefix_filename' => [$_POST['_prefix_' . $question->getEndUserInputName()]],
            ]);
        }

        return $document_ids;
    }

    #[Override]
    public function renderAdministrationTemplate(?Question $question): string
    {
        $template = <<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}

            {{ fields.fileField(
                'default_value',
                '',
                '',
                {
                    'init'           : question is not null ? true: false,
                    'no_label'       : true,
                    'full_width'     : true,
                    'mb'             : '',
                    'multiple'       : true,
                }
            ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'question'       => $question,
        ]);
    }

    #[Override]
    public function renderAdministrationOptionsTemplate(?Question $question): string
    {
        return '';
    }

    #[Override]
    public function renderEndUserTemplate(Question $question): string
    {
        $template = <<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}

            {{ fields.fileField(
                question.getEndUserInputName(),
                "",
                "",
                {
                    'init'                 : true,
                    'no_label'             : true,
                    'full_width'           : true,
                    'mb'                   : '',
                    'multiple'             : true,
                }
            ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'question' => $question,
        ]);
    }

    #[Override]
    public function formatRawAnswer(mixed $answer, Question $question): string
    {
        return implode(', ', array_map(
            fn($document_id) => (new Document())->getById($document_id)->fields['filename'],
            $answer
        ));
    }

    #[Override]
    public function getCategory(): QuestionTypeCategoryInterface
    {
        return QuestionTypeCategory::FILE;
    }

    #[Override]
    public function isAllowedForUnauthenticatedAccess(): bool
    {
        return Config::allowUnauthenticatedUploads();
    }

    #[Override]
    public function getTargetQuestionType(array $rawData): string
    {
        return self::class;
    }


    #[Override]
    public function beforeConversion(array $rawData): void {}

    #[Override]
    public function convertDefaultValue(array $rawData): null
    {
        return null;
    }

    #[Override]
    public function convertExtraData(array $rawData): null
    {
        return null;
    }
}
