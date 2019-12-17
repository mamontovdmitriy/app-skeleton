<?php

namespace App\Security\Authenticators;

use App\Entity\User;
use League\OAuth2\Client\Provider\FacebookUser;

/**
 * Class FacebookAuthenticator
 * @package App\Security
 */
class FacebookAuthenticator extends AbstractSocialAuthenticator
{
    const PROVIDER_NAME = 'facebook';

    /**
     * @param FacebookUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function registerNewUser($socialUser)
    {
        $userData = $socialUser->toArray();
        $userData['fb_id'] = $socialUser->getId();
        $userData['picture'] = $socialUser->getPictureUrl();
        $userData['locale'] = $socialUser->getLocale();

        return $this->getUserService()->registerUser(self::PROVIDER_NAME, $userData);
    }

    /**
     * @param User $currentUser
     * @param FacebookUser $socialUser
     * @return User
     * @throws \Exception
     */
    public function updateCurrentUser($currentUser, $socialUser)
    {
        $currentUser->setFbId($socialUser->getId());
        $this->getUserService()->saveUser($currentUser);

        return $currentUser;
    }
}
