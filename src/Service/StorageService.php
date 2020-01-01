<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;

/**
 * Class ImageStorageService
 * @package App\Service
 */
class StorageService
{
    const DIR_AVATAR = 'avatars';
    const DIR_IMAGES = 'images';

    const NO_PHOTO_FILE = 'no-image-icon.png';

    /**
     * @var FilesystemInterface
     */
    private $publicFileSystem;
    /**
     * @var FilesystemInterface
     */
    private $privateFileSystem;
    /**
     * @var string
     */
    private $storageDir;

    /**
     * ImageStorageService constructor.
     * @param FilesystemInterface $privateFileSystem
     * @param FilesystemInterface $publicFileSystem
     * @param string $storageDir
     */
    public function __construct(FilesystemInterface $privateFileSystem, FilesystemInterface $publicFileSystem, string $storageDir)
    {
        $this->privateFileSystem = $privateFileSystem;
        $this->publicFileSystem = $publicFileSystem;
        $this->storageDir = $storageDir;
    }

    /**
     * @return FilesystemInterface
     */
    public function getPublicFileSystem(): FilesystemInterface
    {
        return $this->publicFileSystem;
    }

    /**
     * @return FilesystemInterface
     */
    public function getPrivateFileSystem(): FilesystemInterface
    {
        return $this->privateFileSystem;
    }

    /**
     * @return string
     */
    public function getStorageDir(): string
    {
        return $this->storageDir;
    }

    /**
     * @param string|null $dir
     * @param string|null $file
     * @return string
     */
    public function getUrl($dir = null, $file = null)
    {
        $dir = $dir ?: self::DIR_IMAGES;
        $file = $file ?: self::NO_PHOTO_FILE;

        return DIRECTORY_SEPARATOR . $this->getStorageDir()
            . DIRECTORY_SEPARATOR . $dir
            . DIRECTORY_SEPARATOR . $file;
    }
}
