<?php

namespace Glpi\Controller\Helpdesk\Translation;

use Config;
use Glpi\Controller\Translation\AbstractTranslationController;
use Glpi\Helpdesk\HelpdeskTranslation;
use Glpi\Http\RedirectResponse;
use Glpi\ItemTranslation\ItemTranslation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddNewHelpdeskTranslationController extends AbstractTranslationController
{
    #[Route("/Helpdesk/Translation/Add", name: "glpi_add_helpdesk_translation", methods: "POST")]
    public function __invoke(Request $request): Response
    {
        // Retrieve and validate the language code from the request
        $language = $request->request->get('language');
        $this->validateLanguage($language);

        $this->createInitialTranslation($language);

        // Redirect with a URL parameter to indicate the modal should be opened
        return new RedirectResponse($this->getRedirectUrl($language));
    }

    protected function getTranslationClass(): ItemTranslation
    {
        return new HelpdeskTranslation();
    }

    protected function getRedirectUrl(?string $language = null): string
    {
        $url = Config::getFormURL();
        return $language ? $url . "?open_translation=$language" : $url;
    }

    protected function getTranslationHandlers(): array
    {
        return (new HelpdeskTranslation())->listTranslationsHandlers();
    }

    protected function getContextTranslations(?string $language = null): array
    {
        return HelpdeskTranslation::getTranslationsForHelpdesk();
    }
}
