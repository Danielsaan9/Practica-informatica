<?php

namespace Glpi\Controller;

use CommonGLPI;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GenericListController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $class = $request->attributes->getString('class');

        $this->checkIsValidClass($class);

        /** @var class-string<CommonGLPI> $class */
        $class::checkReAuthenticationOrRedirect();

        return $this->render('pages/generic_list.html.twig', [
            'class' => $class,
        ]);
    }

    private function checkIsValidClass(string $class): void
    {
        if ($class === '') {
            throw new BadRequestHttpException('The "class" attribute is mandatory for itemtype routes.');
        }

        if (!\class_exists($class)) {
            throw new BadRequestHttpException(\sprintf("Class \"%s\" does not exist.", $class));
        }

        if (!\is_subclass_of($class, CommonGLPI::class)) {
            throw new BadRequestHttpException(\sprintf("Class \"%s\" is not a valid itemtype.", $class));
        }

        if (!$class::canView()) {
            throw new AccessDeniedHttpException();
        }
    }
}
