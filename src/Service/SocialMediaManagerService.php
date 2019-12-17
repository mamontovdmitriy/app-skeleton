<?php

namespace App\Service;

use App\Entity\User;
use App\Security\Authenticators\FacebookAuthenticator;
use App\Security\Authenticators\GoogleAuthenticator;
use App\Security\Authenticators\VKontakteAuthenticator;
use Exception;

/**
 * Class SocialMediaManagerService
 * @package App\Service
 */
class SocialMediaManagerService
{
    const EMPTY_STRING = '';

    /**
     * @param User $user
     * @param string $provider
     * @return bool
     * @throws Exception
     */
    public function detachSocialNetwork(User $user, $provider)
    {
        if ($this->calcActiveNetworkCount($user) < 2) {
            return false;
        }

        switch ($provider) {
            case GoogleAuthenticator::PROVIDER_NAME:
                $user->setGoogleId(self::EMPTY_STRING);
                break;
            case FacebookAuthenticator::PROVIDER_NAME:
                $user->setFbId(self::EMPTY_STRING);
                break;
            case VKontakteAuthenticator::PROVIDER_NAME:
                $user->setVkId(self::EMPTY_STRING);
                break;
            default:
                throw new Exception(sprintf('Unknown provider "%s"!', $provider));
        }

        return true;
    }

    /**
     * @param User $user
     * @return int
     */
    private function calcActiveNetworkCount(User $user)
    {
        return (int)!empty($user->getGoogleId())
            + (int)!empty($user->getFbId())
            + (int)!empty($user->getVkId());
    }
}
