<?php

namespace App\Service;

use App\Entity\AvatarImage;
use App\Entity\User;
use App\Helpers\FileInfo;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AvatarService
 * @package App\Service
 */
class AvatarService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StorageService
     */
    private $storageService;

    /**
     * AvatarService constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param StorageService $storageService
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, StorageService $storageService)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->storageService = $storageService;
    }

    /**
     * Get avatar's relative URL.
     *
     * @param User $user
     * @return string
     */
    public function getAvatarUrl(User $user)
    {
        $fileName = $user->getAvatar()
            ? $user->getAvatar()->getFullFileName()
            : StorageService::NO_PHOTO_FILE;

        return $this->storageService->getUrl(StorageService::DIR_AVATAR, $fileName);
    }

    /**
     * Save avatar from uploaded file OR social media URL
     *
     * @param User $user
     * @param UploadedFile|string $source
     * @return bool
     * @throws Exception
     */
    public function saveAvatar(User $user, $source = null)
    {
        $this->entityManager->beginTransaction();
        try {
            if ($source instanceof File) {
                $url = $source->getRealPath();
                $fileInfo = $source;
            } elseif (is_string($source)) {
                $url = $source;
                $fileInfo = FileInfo::getRemoteFileInfoByUrl($url);
            } else {
                return false;
            }

            $avatar = new AvatarImage(
                $user,
                $fileInfo->getFilename(),
                $fileInfo->getExtension(),
                $fileInfo->getMimeType(),
                $fileInfo->getSize()
            );

            $this->entityManager->persist($avatar);
            $this->entityManager->flush($avatar);

            $file = fopen($url, 'r');
            if (!is_resource($file)) {
                throw new Exception(sprintf('File "%s" is not available!', $url));
            }

            $fileName = StorageService::DIR_AVATAR . DIRECTORY_SEPARATOR . $avatar->getFullFileName();

            $success = $this->storageService->getPublicFileSystem()->writeStream($fileName, $file);

            fclose($file);

            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            $this->logger->warning($exception->getMessage());
            $success = false;
        }

        return $success;
    }
}
