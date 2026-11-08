<?php

namespace Glpi\Helpdesk\Tile;

use CommonDBChild;
use Glpi\Form\AccessControl\FormAccessControlManager;
use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\Form;
use Glpi\Session\SessionInfo;
use Html;
use Override;

final class FormTile extends CommonDBChild implements TileInterface
{
    use TileRightTrait;

    public static string $rightname = 'config';
    public static string $itemtype = Form::class;
    public static string $items_id = 'forms_forms_id';

    private Form $form;

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }

    #[Override]
    public function getLabel(): string
    {
        return Form::getTypeName(1);
    }

    #[Override]
    public function post_getFromDB(): void
    {
        $form = $this->getItem();
        if (!($form instanceof Form)) {
            throw new InvalidTileException("Unable to load linked form");
        } else {
            $this->form = $form;
        }
    }

    #[Override]
    public function getTitle(): string
    {
        return $this->form->getServiceCatalogItemTitle();
    }

    #[Override]
    public function getDescription(): string
    {
        return $this->form->getServiceCatalogItemDescription();
    }

    #[Override]
    public function getIllustration(): string
    {
        return $this->form->getServiceCatalogItemIllustration();
    }

    #[Override]
    public function getTileUrl(): string
    {
        return Html::getPrefixedUrl('/Form/Render/' . $this->form->getID());
    }

    #[Override]
    public function isAvailable(SessionInfo $session_info): bool
    {
        $form_access_manager = FormAccessControlManager::getInstance();

        // Form must be active
        if (!$this->form->isActive()) {
            return false;
        }

        // Form must not be deleted
        if ($this->form->isDeleted()) {
            return false;
        }

        // Check that the form entity is visible
        if (!$this->form->isAccessibleFromEntities($session_info->getActiveEntitiesIds())) {
            return false;
        }

        // Check if the user can answer the form
        $form_access_params = new FormAccessParameters(session_info: $session_info);
        if (!$form_access_manager->canAnswerForm($this->form, $form_access_params)) {
            return false;
        }

        return true;
    }

    #[Override]
    public function getDatabaseId(): int
    {
        return $this->fields['id'];
    }

    #[Override]
    public function getConfigFieldsTemplate(): string
    {
        return "pages/admin/form_tile_config_fields.html.twig";
    }

    #[Override]
    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb(
            [
                Item_Tile::class,
            ]
        );
    }

    public function getFormId(): int
    {
        return $this->fields['forms_forms_id'] ?? 0;
    }
}
