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
        // todo setUpdated is deprecated. You should use setUpdatedAt()
        if (method_exists($this, 'setUpdated')) {
            $this->setUpdated(new DateTime());
        }

        if (method_exists($this, 'setUpdatedAt')) {
            $this->setUpdatedAt(new DateTime());
        }
    }
}
