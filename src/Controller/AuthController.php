<?php

namespace App\Controller;

use App\Entity\User;
use App\Helpers\Controller;
use App\Helpers\Flash;
use App\Service\SocialMediaManagerService;
use App\Service\UserService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends Controller
{
    /**
     * Login page.
     *
     * @return Response
     */
    public function login()
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('default');
        }

        return $this->render('auth/login.html.twig');
    }

    /**
     * Login via social networks.
     *
     * @param string $network
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function via(string $network, ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient($network)->redirect([], []);
    }

    /**
     * Handle a social network connection.
     *
     * @param string $network
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function check(string $network, Request $request)
    {
        if (!$this->getUser() || !$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login', ['_locale' => $this->getMyLocale($request)]);
        }

        return $this->redirect($request->headers->get('referer', '/'));
    }

    /**
     * Handle to detach a social network account.
     *
     * @param string $network
     * @param Request $request
     * @param UserService $userService,
     * @param SocialMediaManagerService $socialMediaManagerService
     * @return RedirectResponse
     * @throws \Exception
     */
    public function detach(
        string $network,
        Request $request,
        UserService $userService,
        SocialMediaManagerService $socialMediaManagerService
    )
    {
        if (!$this->getUser() || !$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login', ['_locale' => $this->getMyLocale($request)]);
        }

        /** @var User $user */
        $user = $this->getUser();
        $success = $socialMediaManagerService->detachSocialNetwork($user, $network);
        if ($success) {
            $userService->saveUser($user);

            $this->addFlash(Flash::TYPE_SUCCESS, $this->trans('message.auth.user_detach'));
        } else {
            $this->addFlash(Flash::TYPE_ERROR, $this->trans('error.auth.user_detach'));
        }

        return $this->redirect($request->headers->get('referer', '/'));
    }
}
