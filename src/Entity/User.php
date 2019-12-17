<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.11.2019
 * Time: 10:20
 */

namespace App\Entity;

use App\Helpers\TimestampField;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users", indexes={
 *     @ORM\Index(name="user_status_idx", columns={"status"}),
 *     @ORM\Index(name="user_google_idx", columns={"google_id"}),
 *     @ORM\Index(name="user_fb_idx", columns={"fb_id"}),
 *     @ORM\Index(name="user_vk_idx", columns={"vk_id"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    const STATUS_NEW = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_BANNED = 2;
    const STATUS_DELETED = 3;

    use TimestampField;

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="created", type="datetime", options={"comment":"Дата создания"})
     *
     * @var DateTime
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime", options={"comment":"Дата изменения"})
     *
     * @var DateTime
     */
    private $updated;

    /**
     * @ORM\Column(name="status", type="integer", options={"default":0, "comment":"Статус пользователя"})
     *
     * @var int
     */
    private $status;

    /**
     * @ORM\Column(name="locale", type="string", options={"default":"", "comment":"Локаль пользователя"})
     *
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(name="name", type="string", length=255, options={"default":"", "comment":"Имя пользователя"})
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\Column(name="email", type="string", length=64, options={"default":"", "comment":"Email пользователя"})
     *
     * @var string
     */
    private $email = '';

    /**
     * @ORM\Column(name="google_id", type="string", length=64, options={"default":"", "comment":"Google ID"})
     *
     * @var string
     */
    private $googleId = '';

    /**
     * @ORM\Column(name="fb_id", type="string", length=64, options={"default":"", "comment":"Facebook ID"})
     *
     * @var string
     */
    private $fbId = '';

    /**
     * @ORM\Column(name="vk_id", type="string", length=64, options={"default":"", "comment":"VKontakte ID"})
     *
     * @var string
     */
    private $vkId = '';

    /**
     * User constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setCreated(new DateTime());
        $this->setUpdated($this->getCreated());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * @param string $fbId
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;
    }

    /**
     * @return string
     */
    public function getVkId()
    {
        return $this->vkId;
    }

    /**
     * @param string $vkId
     */
    public function setVkId($vkId)
    {
        $this->vkId = $vkId;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getId();
    }

    public function eraseCredentials()
    {
        return null;
    }
}
