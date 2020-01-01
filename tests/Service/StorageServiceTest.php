<?php

namespace App\Tests\Service;

use App\Service\StorageService;
use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    const STORAGE_NAME = 'storage';

    const AVATAR_DIR = 'avatars';
    const IMAGE_DIR = 'images';


    public function testStorageDir()
    {
        $imageStorage = $this->getStorageService();
        $this->assertEquals(self::STORAGE_NAME, $imageStorage->getStorageDir());
    }

    public function testStorageGetUrl()
    {
        $imageStorage = $this->getStorageService();

        $this->assertEquals('/storage/images/no-image-icon.png', $imageStorage->getUrl());
        $this->assertEquals('/storage/images/no-image-icon.png', $imageStorage->getUrl(StorageService::DIR_IMAGES));
        $this->assertEquals('/storage/avatars/no-image-icon.png', $imageStorage->getUrl(StorageService::DIR_AVATAR));
        $this->assertEquals('/storage/avatars/1.jpeg', $imageStorage->getUrl(StorageService::DIR_AVATAR, '1.jpeg'));
        $this->assertEquals('/storage/avatars/2.png', $imageStorage->getUrl(StorageService::DIR_AVATAR, '2.png'));
        $this->assertEquals('/storage/images/3.jpeg', $imageStorage->getUrl(StorageService::DIR_IMAGES, '3.jpeg'));
        $this->assertEquals('/storage/images/4.png', $imageStorage->getUrl(StorageService::DIR_IMAGES, '4.png'));
        $this->assertEquals('/storage/images/5.jpeg', $imageStorage->getUrl(null, '5.jpeg'));

        $this->assertEquals('/storage/images/no-image-icon.png', $imageStorage->getUrl('', ''));
        $this->assertEquals('/storage/111/222', $imageStorage->getUrl('111', '222'));
    }

    /**
     * @return StorageService
     * @throws \Exception
     */
    private function getStorageService()
    {
        /** @var FilesystemInterface $publicFileSystem */
        $publicFileSystem = self::createMock(FilesystemInterface::class);

        return new StorageService($publicFileSystem, $publicFileSystem, self::STORAGE_NAME);
    }
}
