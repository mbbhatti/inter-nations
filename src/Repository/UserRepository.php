<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Add new user
     *
     * @param array $data
     * @return int
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(array $data): int
    {
        $dateTime = new DateTime('now');
        $manager = $this->getEntityManager();

        $user = new User();
        $user->setName($data['name']);
        $user->setCreatedAt($dateTime);
        $user->setUpdatedAt($dateTime);

        $manager->persist($user);
        $manager->flush();

        return $user->getId();
    }

    /**
     * Remove a user
     *
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}

