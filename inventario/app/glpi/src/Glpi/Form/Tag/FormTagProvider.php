<?php

namespace Glpi\Form\Tag;

use Glpi\Form\AnswersSet;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Override;

final class FormTagProvider implements TagProviderInterface, TagWithIdValueInterface
{
    #[Override]
    public function getTagColor(): string
    {
        return "yellow";
    }

    #[Override]
    public function getTags(Form $form): array
    {
        return [$this->getTagForForm($form)];
    }

    #[Override]
    public function getTagContentForValue(
        string $value,
        AnswersSet $answers_set
    ): string {
        $id = (int) $value;

        $form = Form::getById($id);
        if ($form === false) {
            return '';
        }
        return FormTranslation::translate($form, Form::TRANSLATION_KEY_NAME) ?? $form->fields['name'];
    }

    #[Override]
    public function getItemtype(): string
    {
        return Form::class;
    }

    #[Override]
    public function getTagFromRawValue(string $value): ?Tag
    {
        $form = Form::getById((int) $value);
        if (!$form) {
            return null;
        }

        return $this->getTagForForm($form);
    }

    public function getTagForForm(Form $form): Tag
    {
        return new Tag(
            label: sprintf(__('Form name: %s'), $form->fields['name']),
            value: $form->getId(),
            provider: $this,
        );
    }
}
