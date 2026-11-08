<?php

use Glpi\ContentTemplates\ParametersPreset;
use Glpi\ContentTemplates\TemplateManager;

/**
 * Base template class
 *
 * @since 10.0.0
 */
abstract class AbstractITILChildTemplate extends CommonDropdown
{
    public function showForm($ID, array $options = [])
    {
        if (!parent::showForm($ID, $options)) {
            return false;
        }

        // Add autocompletion for ticket properties (twig templates)
        $parameters = ParametersPreset::getForAbstractTemplates();
        Html::activateUserTemplateAutocompletion(
            'textarea[name=content]',
            TemplateManager::computeParameters($parameters)
        );

        // Add related documentation
        Html::addTemplateDocumentationLinkJS(
            'textarea[name=content]',
            ParametersPreset::ITIL_CHILD_TEMPLATE
        );

        return true;
    }

    public function prepareInputForAdd($input)
    {
        $input = parent::prepareInputForUpdate($input);

        if (!$this->validateContentInput($input)) {
            return false;
        }

        return $input;
    }

    public function prepareInputForUpdate($input)
    {
        $input = parent::prepareInputForUpdate($input);

        if (!$this->validateContentInput($input)) {
            return false;
        }

        return $input;
    }

    /**
     * Validate 'content' field from input.
     *
     * @param array $input
     *
     * @return bool
     */
    protected function validateContentInput(array $input): bool
    {
        if (!isset($input['content'])) {
            return true;
        }

        $err_msg = null;
        if (!TemplateManager::validate($input['content'], $err_msg)) {
            Session::addMessageAfterRedirect(
                htmlescape(sprintf('%s: %s', __('Content'), $err_msg)),
                false,
                ERROR
            );
            $this->saveInput();
            return false;
        }

        return true;
    }

    /**
     * Get content rendered by template engine, using given ITIL item to build parameters.
     *
     * @param CommonITILObject $itil_item
     *
     * @return string
     */
    public function getRenderedContent(CommonITILObject $itil_item): string
    {
        if (empty($this->fields['content'])) {
            return '';
        }

        $content = $this->fields['content'];
        $content = DropdownTranslation::getTranslatedValue(
            $this->getID(),
            static::class,
            'content',
            $_SESSION['glpilanguage'],
            $content
        );

        $html = TemplateManager::renderContentForCommonITIL(
            $itil_item,
            $content
        );

        if ($html === null) {
            $html = $content;
        }

        return $html;
    }
}
