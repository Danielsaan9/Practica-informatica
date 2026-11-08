<?php

namespace Glpi\Form\Tag;

use Glpi\Form\AnswersSet;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Glpi\Form\Section;
use Override;

final class SectionTagProvider implements TagProviderInterface, TagWithIdValueInterface
{
    #[Override]
    public function getTagColor(): string
    {
        return "cyan";
    }

    #[Override]
    public function getTags(Form $form): array
    {
        $tags = [];
        foreach ($form->getSections() as $section) {
            $tags[] = $this->getTagForSection($section);
        }

        return $tags;
    }

    #[Override]
    public function getTagContentForValue(
        string $value,
        AnswersSet $answers_set
    ): string {
        $id = (int) $value;

        $section = Section::getById($id);
        if (!$section) {
            return '';
        }
        return FormTranslation::translate($section, Section::TRANSLATION_KEY_NAME) ?? $section->fields['name'];
    }

    #[Override]
    public function getItemtype(): string
    {
        return Section::class;
    }

    #[Override]
    public function getTagFromRawValue(string $value): ?Tag
    {
        $section = Section::getById((int) $value);
        if (!$section) {
            return null;
        }

        return $this->getTagForSection($section);
    }

    public function getTagForSection(Section $section): Tag
    {
        return new Tag(
            label: sprintf(__('Section: %s'), $section->fields['name']),
            value: $section->getId(),
            provider: $this,
        );
    }
}
