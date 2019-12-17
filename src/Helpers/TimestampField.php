<?php

namespace App\Helpers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampField
 * @package App\Helpers
 * @ORM\HasLifecycleCallbacks()
 */
Trait TimestampField
{
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateTimestamp()
    {
        if (method_exists($this, 'setUpdated')) {
            $this->setUpdated(new DateTime());
        }
    }
}
