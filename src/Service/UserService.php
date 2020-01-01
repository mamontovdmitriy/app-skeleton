<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Authenticators\FacebookAuthenticator;
use App\Security\Authenticators\GoogleAuthenticator;
use App\Security\Authenticators\VKontakteAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var AvatarService
     */
    private $avatarService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $repoUser;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * UserService constructor.
     * @param AvatarService $avatarService
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param string $defaultLocale
     */
    public function __construct(
        AvatarService $avatarService,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        $defaultLocale
    )
    {
        $this->avatarService = $avatarService;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->defaultLocale = $defaultLocale;

        $this->repoUser = $this->entityManager->getRepository(User::class);
    }

    /**
     * @return object|null
     */
    public function getCurrentUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        return \is_object($user = $token->getUser()) ? $user : null;
    }

    /**
     * @param $id
     * @return User|null
     * @throws NonUniqueResultException
     * @throws NotFoundHttpException
     */
    public function findUserById($id)
    {
        $user = $this->repoUser->getUserById($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }

    /**
     * @param string $userSocialId
     * @param string|null $provider
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findUserBySocialId($userSocialId, $provider = null)
    {
        return $this->repoUser->getUserById($userSocialId, $provider);
    }

    /**
     * @param string $provider
     * @param array $userData
     * @return User
     * @throws Exception
     */
    public function registerUser($provider, $userData)
    {
        $user = new User();
        $user->setCreated(new DateTime());
        $user->setUpdated(new DateTime());
        $user->setStatus(User::STATUS_CONFIRMED);
        $user->setEmail($userData['email'] ?? '');
        $user->setName($userData['name'] ?? '');
        $user->setLocale($userData['locale'] ?? $this->defaultLocale);

        $this->saveSocialId($user, $provider, $userData);
        $this->saveUser($user);

        $this->avatarService->saveAvatar($user, $userData['picture'] ?? '');

        return $user;
    }

    /**
     * @param User $user
     * @param $provider
     * @param $userData
     */
    private function saveSocialId(User $user, $provider, $userData)
    {
        switch ($provider) {
            case GoogleAuthenticator::PROVIDER_NAME:
                $user->setGoogleId($userData['google_id'] ?? '');
                break;

            case FacebookAuthenticator::PROVIDER_NAME:
                $user->setFbId($userData['fb_id'] ?? '');
                break;

            case VKontakteAuthenticator::PROVIDER_NAME:
                $user->setVkId($userData['vk_id'] ?? '');
                break;
        }
    }

    /**
     * @param User $user
     */
    public function saveUser(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }
}
