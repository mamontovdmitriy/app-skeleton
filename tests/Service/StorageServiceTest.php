<?php

namespace App\Tests\Service;

use App\Service\StorageService;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    const PROJECT_PATH = '/var/www/project';
    const STORAGE_PATH = 'public/storage';
    const STORAGE_NAME = 'storage';

    const IMAGE_DIR = 'images';


    public function testStoragePath()
    {
        $imageStorage = $this->getStorageService();
        $this->assertStringEndsWith(self::STORAGE_NAME, $imageStorage->getPathStorage());
    }

    public function testStorageName()
    {
        $imageStorage = $this->getStorageService();
        $this->assertEquals(self::STORAGE_NAME, $imageStorage->getDirStorage());
    }

    public function testSomeDirUrl()
    {
        $imageStorage = $this->getStorageService();
        $url = DIRECTORY_SEPARATOR . self::STORAGE_NAME . DIRECTORY_SEPARATOR . self::IMAGE_DIR;
        $this->assertEquals($url, $imageStorage->getUrl(self::IMAGE_DIR));
    }

    public function testSomeDirPath()
    {
        $imageStorage = $this->getStorageService();
        $path = self::STORAGE_PATH . DIRECTORY_SEPARATOR . self::IMAGE_DIR;
        $this->assertEquals($path, $imageStorage->getPath(self::IMAGE_DIR));
    }
    /**
     * @return StorageService
     * @throws \Exception
     */
    private function getStorageService()
    {
        return new StorageService(self::PROJECT_PATH, self::STORAGE_PATH, self::STORAGE_NAME);
    }
}
