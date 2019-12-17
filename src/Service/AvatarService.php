<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AvatarService
 * @package App\Service
 */
class AvatarService
{
    const EXTENSION = '.jpeg';

    /**
     * @var StorageService
     */
    private $storageService;

    /**
     * @var string
     */
    private $pathStorageAvatar;

    /**
     * @var string
     */
    private $urlStorageAvatar;

    /**
     * AvatarService constructor.
     * @param StorageService $storageService
     */
    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;

        $this->pathStorageAvatar = $this->storageService->getPath(StorageService::DIR_AVATAR);
        $this->urlStorageAvatar = $this->storageService->getUrl(StorageService::DIR_AVATAR);
    }

    /**
     * Get path for avatar file.
     *
     * @param int $userId
     * @param bool $isUrl
     * @return string
     */
    public function getAvatarPath($userId, $isUrl = true)
    {
        if ($isUrl) {
            return $this->urlStorageAvatar . DIRECTORY_SEPARATOR . $userId . self::EXTENSION;
        }

        return $this->pathStorageAvatar . DIRECTORY_SEPARATOR . $userId . self::EXTENSION;
    }

    /**
     * Download and save avatar from social network.
     *
     * @param int $userId
     * @param string $url
     * @throws Exception
     */
    public function putAvatar($userId, $url)
    {
        $content = file_get_contents($url);
        if (empty($content)) {
            throw new NotFoundHttpException(sprintf('File "%s" is not available!', $url));
        }

        $fullFileName = $this->pathStorageAvatar . DIRECTORY_SEPARATOR . $userId . self::EXTENSION;

        $success = file_put_contents($fullFileName, $content);
        if (!$success) {
            throw new Exception(sprintf('File "%s" is not saved!', $fullFileName));
        }
    }

    /**
     * Upload new avatar for user.
     *
     * @param int $userId
     * @param File|null $uploadFile
     * @return bool
     */
    public function uploadAvatar($userId, File $uploadFile = null)
    {
        if (!$uploadFile) {
            return false;
        }

        try {
            // todo NOT SAFE - uploaded image needs to resize
            $uploadFile->move($this->pathStorageAvatar, $userId. self::EXTENSION);
        } catch (FileException $e) {
            return false;
        }

        return true;
    }
}
