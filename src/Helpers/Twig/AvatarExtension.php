<?php

namespace App\Helpers\Twig;

use App\Entity\User;
use App\Service\AvatarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AvatarExtension
 * @package App\Helpers\Twig
 */
class AvatarExtension extends AbstractExtension
{
    /**
     * @var AvatarService
     */
    private $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('avatar', [$this, 'getAvatar']),
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public function getAvatar(User $user)
    {
        return $this->avatarService->getAvatarPath($user->getId());
    }
}
