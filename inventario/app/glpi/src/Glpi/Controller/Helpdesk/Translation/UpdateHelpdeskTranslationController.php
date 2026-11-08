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

final class UpdateHelpdeskTranslationController extends AbstractTranslationController
{
    #[Route("/Helpdesk/Translation/{language}", name: "glpi_update_helpdesk_translation", methods: "POST")]
    public function __invoke(Request $request, string $language): Response
    {
        // Validate the language code
        $this->validateLanguage($language);

        $input = $request->request->all();
        if ($this->processTranslations($input['translations'] ?? [], $language)) {
            $this->addSuccessMessage($language);
        }

        // Redirect with a URL parameter to indicate the modal should be opened
        return new RedirectResponse($this->getRedirectUrl($language));
    }

    protected function getTranslationClass(): ItemTranslation
    {
        return new HelpdeskTranslation();
    }

    protected function getRedirectUrl(?string $language = null): string
    {
        return Config::getFormURL();
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
