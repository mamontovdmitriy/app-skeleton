<?php

namespace App\Security\Authenticators;

use App\Entity\User;
use J4k\OAuth2\Client\Provider\VkontakteUser;

/**
 * Class VKontakteAuthenticator
 * @package App\Security
 */
class VKontakteAuthenticator extends AbstractSocialAuthenticator
{
    const PROVIDER_NAME = 'vkontakte';

    /**
     * @param VkontakteUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function registerNewUser($socialUser)
    {
        $userData = $socialUser->toArray();
        $userData['vk_id'] = $socialUser->getId();
        $userData['name'] = $socialUser->getFirstName() . ' ' . $socialUser->getLastName();
        $userData['picture'] = $socialUser->getPhotoMax();

        return $this->getUserService()->registerUser(self::PROVIDER_NAME, $userData);
    }

    /**
     * @param User $currentUser
     * @param VkontakteUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function updateCurrentUser($currentUser, $socialUser)
    {
        $currentUser->setVkId($socialUser->getId());
        $this->getUserService()->saveUser($currentUser);

        return $currentUser;
    }
}
