<?php

namespace App\Security\Authenticators;

use App\Entity\User;
use League\OAuth2\Client\Provider\GoogleUser;

/**
 * Class GoogleAuthenticator
 * @package App\Security
 */
class GoogleAuthenticator extends AbstractSocialAuthenticator
{
    const PROVIDER_NAME = 'google';

    /**
     * @param GoogleUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function registerNewUser($socialUser)
    {
        $userData = $socialUser->toArray();
        $userData['google_id'] = $socialUser->getId();
        $userData['picture'] = $socialUser->getAvatar();
        $userData['locale'] = $socialUser->getLocale();

        return $this->getUserService()->registerUser(self::PROVIDER_NAME, $userData);
    }

    /**
     * @param User $currentUser
     * @param GoogleUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function updateCurrentUser($currentUser, $socialUser)
    {
        $currentUser->setGoogleId($socialUser->getId());
        $this->getUserService()->saveUser($currentUser);

        return $currentUser;
    }
}
