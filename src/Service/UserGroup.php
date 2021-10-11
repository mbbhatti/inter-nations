<?php

namespace App\Service;

/**
 * Class UserGroup
 * @package App\Service
 */
class UserGroup
{
    /**
     * Check user or member already assigned to group or not
     *
     * @param array $groups
     * @param int $groupId
     * @return bool
     */
    public function isMemberAssigned(array $groups, int $groupId): bool
    {
        foreach ($groups as $group) {
            if ($group->getId() === $groupId) {
                return true;
            }
        }

        return false;
    }
}

