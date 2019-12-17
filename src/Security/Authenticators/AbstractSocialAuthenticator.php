<?php

namespace App\Security\Authenticators;

use App\Entity\User;
use App\Helpers\Flash;
use App\Service\UserService;
use Doctrine\ORM\NonUniqueResultException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractSocialAuthenticator
 * @package App\Security\Authenticators
 */
abstract class AbstractSocialAuthenticator extends SocialAuthenticator
{
    /**
     * Social provider name
     */
    const PROVIDER_NAME = '';

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    abstract public function registerNewUser($socialUser);

    abstract public function updateCurrentUser($currentUser, $socialUser);

    /**
     * AbstractSocialAuthenticator constructor.
     * @param RouterInterface $router
     * @param ClientRegistry $clientRegistry
     * @param UserService $userService
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(
        RouterInterface $router,
        ClientRegistry $clientRegistry,
        UserService $userService,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator
    ) {
        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
        $this->userService = $userService;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        $currentPath = $this->router->generate('connect_check', ['network' => static::PROVIDER_NAME]);

        return $request->isMethod('GET') && $request->getPathInfo() === $currentPath;
    }

    /**
     * @param Request $request
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getClient());
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return ClientRegistry
     */
    public function getClientRegistry()
    {
        return $this->clientRegistry;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }

    /**
     * @return OAuth2ClientInterface
     */
    protected function getClient()
    {
        return $this->clientRegistry->getClient(static::PROVIDER_NAME);
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|UserInterface|null
     * @throws \Exception
     * @throws NonUniqueResultException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUserService()->getCurrentUser();
        /** @var FacebookUser $socialUser */
        $socialUser = $this->getClient()->fetchUserFromToken($credentials);

        $user = $this->getUserService()->findUserBySocialId($socialUser->getId(), static::PROVIDER_NAME);
        if ($user) {
            if ($currentUser && $currentUser->getId() !== $user->getId()) {
                return null; //error
            }
            return $user; // login
        } else {
            if ($currentUser) {
                return $this->updateCurrentUser($currentUser, $socialUser); // attach account
            }
            return $this->registerNewUser($socialUser); // register
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->flashBag->add(Flash::TYPE_ERROR, $this->translator->trans('error.auth.user_exists'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }
}
