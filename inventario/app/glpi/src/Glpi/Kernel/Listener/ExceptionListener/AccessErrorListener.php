<?php

namespace Glpi\Kernel\Listener\ExceptionListener;

use Glpi\Application\View\TemplateRenderer;
use Glpi\Exception\AuthenticationFailedException;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\HttpException;
use Glpi\Exception\SessionExpiredException;
use Glpi\Http\RedirectResponse;
use Session;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class AccessErrorListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 1],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // Ignore sub-requests.
            return;
        }

        $request = $event->getRequest();
        $throwable = $event->getThrowable();

        $response = null;

        // On profile change, we will redirect the user to the home page in case
        // of access errors for the current page.
        $redirect_to_home_on_error = $event->getRequest()
            ->query
            ->getBoolean('_redirected_from_profile_selector')
        ;

        // Handle SessionExpiredException BEFORE checking if request is AJAX
        // This ensures that expired sessions return proper HTTP 401 response for AJAX requests
        if ($throwable instanceof SessionExpiredException) {
            Session::destroy(); // destroy the session to prevent pesistence of unexpected data

            if ($request->isXmlHttpRequest() || $request->getPreferredFormat() !== 'html') {
                // For AJAX/JSON requests, convert the error into a HttpException
                $http_exception = new HttpException(401, 'Session expired.');
                $http_exception->setMessageToDisplay(__('Your session has expired. Please log in again.'));
                throw $http_exception;
            } else {
                // For HTML requests, redirect to login page (existing behavior).
                // The original URL is kept as redirection target only for safe methods, as replaying it with
                // a `GET` method after the login may target a route that does not accept this method.
                // The `redirect` parameter must not be empty, as it is required to display the "session expired" message.
                $redirect = in_array($request->getRealMethod(), [Request::METHOD_GET, Request::METHOD_HEAD], true)
                    ? $request->getPathInfo() . '?' . $request->getQueryString()
                    : '/';
                $response = new RedirectResponse(
                    sprintf(
                        '%s/?redirect=%s&error=3',
                        $request->getBasePath(),
                        \rawurlencode($redirect)
                    )
                );
            }
        }

        // Skip AJAX requests for OTHER exceptions (not SessionExpiredException)
        if ($response === null && ($request->isXmlHttpRequest() || $request->getPreferredFormat() !== 'html')) {
            // Do not redirect AJAX requests nor requests that expect the response to be something else than HTML.
            return;
        }

        if (
            $response === null
            && $throwable instanceof AccessDeniedHttpException
            && $redirect_to_home_on_error
        ) {
            $request = $event->getRequest();
            $response = new RedirectResponse(
                sprintf(
                    '%s/front/central.php',
                    $request->getBasePath()
                )
            );
        }

        if ($response === null && $throwable instanceof AuthenticationFailedException) {
            $login_url = sprintf(
                '%s/front/logout.php?noAUTO=1',
                $request->getBasePath()
            );
            $redirect = ($request->request->get('redirect') ?: $request->query->get('redirect')) ?: null;
            if ($redirect !== null) {
                $login_url .= '&redirect=' . \rawurlencode($redirect);
            }
            $response = new Response(
                content: TemplateRenderer::getInstance()->render(
                    'pages/login_error.html.twig',
                    [
                        'errors'    => $throwable->getAuthenticationErrors(),
                        'login_url' => $login_url,
                    ]
                ),
                status: 400
            );
        }

        if ($response !== null) {
            $event->setResponse($response);
        }
    }
}
