<?php

namespace App\Entity;

use App\Helpers\TimestampField;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Class AvatarImage
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\AvatarImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AvatarImage
{
    use TimestampField;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, options={"comment":"Оригинальное имя файла"})
     */
    private $originalName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment":"Mime-тип"})
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer", options={"default":0, "comment":"Размер файла"})
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, options={"default":"", "comment":"Расширение файла"})
     */
    private $extension;

    /**
     * @ORM\Column(type="boolean", options={"default":true, "comment":"Выбран по-умолчанию"})
     */
    private $active;

    /**
     * @ORM\Column(type="boolean", options={"default":false, "comment":"Маркер удаления"})
     */
    private $deleted;

    /**
     * @ORM\Column(type="datetime", options={"comment":"Дата создания"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"comment":"Дата изменения"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="avatars")
     */
    private $user;

    /**
     * AvatarImage constructor.
     * @param User $user
     * @param string $originalName
     * @param string|null $extension
     * @param string|null $mimeType
     * @param int $size
     * @param bool $deleted
     * @param bool $active
     * @throws Exception
     */
    public function __construct(
        User $user,
        string $originalName,
        string $extension = null,
        string $mimeType = null,
        int $size = 0,
        bool $deleted = false,
        bool $active = true
    )
    {
        $this->setUser($user);
        $this->setOriginalName($originalName);
        $this->setExtension($extension);
        $this->setMimeType($mimeType);
        $this->setSize($size);
        $this->setDeleted($deleted);
        $this->setActive($active);
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFullFileName(): string
    {
        return $this->getId() . '.' . $this->getExtension();
    }
}
