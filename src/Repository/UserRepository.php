<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $id
     * @param string|null $provider
     * @return User|null
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getUserById($id, $provider = null)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->where(sprintf('u.%s = :id', $this->getIdFieldName($provider)))
            ->setParameter('id', $id)
            ->andWhere('u.status NOT IN (:statuses)')
            ->setParameter('statuses', [User::STATUS_BANNED, User::STATUS_DELETED]);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string|null $provider
     * @return mixed
     * @throws Exception
     */
    private function getIdFieldName($provider = null)
    {
        $fields = [
            null => 'id',
            'facebook' => 'fbId',
            'google' => 'googleId',
            'vkontakte' => 'vkId',
        ];

        if (!isset($fields[$provider])) {
            throw new Exception('No field name');
        }

        return $fields[$provider];
    }
}
