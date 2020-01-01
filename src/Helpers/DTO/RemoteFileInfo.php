<?php

namespace App\Helpers\DTO;

/**
 * Class RemoteFile
 * @package App\Helpers\DTO
 */
class RemoteFileInfo
{
    /**
     * @var string
     */
    private $filename;
    /**
     * @var string
     */
    private $extension;
    /**
     * @var string
     */
    private $mimeType;
    /**
     * @var int
     */
    private $size;

    /**
     * RemoteFileInfo constructor.
     * @param string $filename
     * @param string $extension
     * @param string $mimeType
     * @param int $size
     */
    public function __construct(string $filename, string $extension = null, string $mimeType = null, int $size = 0)
    {
        $this->filename = $filename;
        $this->extension = $extension;
        $this->mimeType = $mimeType;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return self
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return self
     */
    public function setExtension(?string $extension = null): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setMimeType(?string $mimeType = null): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size = 0): self
    {
        $this->size = $size;

        return $this;
    }
}
