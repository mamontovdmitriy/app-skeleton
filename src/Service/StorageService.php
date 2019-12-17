<?php

namespace App\Service;

use Exception;

/**
 * Class ImageStorageService
 * @package App\Service
 */
class StorageService
{
    const DIR_AVATAR = 'avatars';
    const DIR_IMAGES = 'images';

    /**
     * @var string
     */
    private $pathStorage;
    /**
     * @var string
     */
    private $dirStorage;

    /**
     * ImageStorageService constructor.
     * @param string $pathProject
     * @param string $pathStorage
     * @param string $dirStorage
     * @throws Exception
     */
    public function __construct($pathProject, $pathStorage, $dirStorage)
    {
        if (!file_exists($pathStorage)) {
            $pathStorage = $pathProject . DIRECTORY_SEPARATOR . $pathStorage;

            if (!file_exists($pathStorage)) {
                throw new Exception(sprintf('Path "%s" doesn`t exist!', $pathStorage));
            }
        }

        if (!is_dir($pathStorage)) {
            throw new Exception(sprintf('Path "%s" is not a directory!', $pathStorage));
        }

        if (!is_writable($pathStorage)) {
            throw new Exception(sprintf('Dir "%s" doesn`t writable!', $pathStorage));
        }

        $this->pathStorage = $pathStorage;
        $this->dirStorage = $dirStorage;
    }

    /**
     * @return string
     */
    public function getDirStorage()
    {
        return $this->dirStorage;
    }

    /**
     * @return string
     */
    public function getPathStorage()
    {
        return $this->pathStorage;
    }

    /**
     * @param string $dir
     * @return string
     */
    public function getPath($dir)
    {
        return $this->getPathStorage() . DIRECTORY_SEPARATOR . $dir;
    }

    /**
     * @param string $dir
     * @return string
     */
    public function getUrl($dir)
    {
        return DIRECTORY_SEPARATOR . $this->getDirStorage() . DIRECTORY_SEPARATOR . $dir;
    }
}
