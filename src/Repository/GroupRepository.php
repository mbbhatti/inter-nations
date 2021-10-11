<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\Group;
use App\Service\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupRepository
 * @package App\Repository
 */
class GroupRepository extends ServiceEntityRepository
{
    /**
     * @var UserGroup
     */
    private $userGroup;

    /**
     * GroupRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param UserGroup $userGroup
     */
    public function __construct(ManagerRegistry $registry, UserGroup $userGroup)
    {
        parent::__construct($registry, Group::class);
        $this->userGroup = $userGroup;
    }

    /**
     * Add new group
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

        $group = new Group();
        $group->setName($data['name']);
        $group->setCreatedAt($dateTime);
        $group->setUpdatedAt($dateTime);

        $manager->persist($group);
        $manager->flush();

        return $group->getId();
    }

    /**
     * Remove a group
     *
     * @param int $id
     * @return bool|null
     */
    public function remove(int $id): ?bool
    {
        $group = $this->find($id);
        if ($group === null) {
            return $group;
        }

        // Group only be removed if it has no user or member.
        // Check group has users or not.
        $users = $group->getUsers();
        if (!empty($users)) {
            return false;
        }

        return $this->createQueryBuilder('g')
            ->delete()
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * Assign user or member to a group
     *
     * @param array $data
     * @return bool|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function assignUser(array $data): ?bool
    {
        $groupId = $data['group_id'];
        $userId = $data['user_id'];
        $group = $this->_em->getRepository(Group::class)->findOneById($groupId);
        $user = $this->_em->getRepository(User::class)->findOneById($userId);
        if ($group !== null && $user !== null) {
            if (!$this->userGroup->isMemberAssigned($user->getGroups(), $groupId)) {
                $user->addGroup($group);
                $group->addUser($user);

                $this->_em->persist($group);
                $this->_em->persist($user);
                $this->_em->flush();

                return true;
            }

            return null;
        }

        return false;
    }

    /**
     * Unassigned group user or member
     *
     * @param Request $request
     * @return bool|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeUser($request): ?bool
    {
        $groupId = $request->get('gid');
        $userId = $request->get('uid');
        $group = $this->_em->getRepository(Group::class)->findOneById($groupId);
        $user = $this->_em->getRepository(User::class)->findOneById($userId);
        if ($group !== null && $user !== null) {
            if ($this->userGroup->isMemberAssigned($user->getGroups(), $groupId)) {
                $user->removeGroup($group);
                $group->removeUser($user);

                $this->_em->persist($group);
                $this->_em->persist($user);
                $this->_em->flush();

                return true;
            }

            return null;
        }

        return false;
    }
}

