<?php

namespace App\Service;

use App\Entity\Group;

/**
 * Class GroupValidator
 * @package App\Service
 */
class GroupValidator extends Validator
{
    /**
     * Validate request with defined entity rules
     *
     * @param array $data
     * @return mixed
     */
    public function isValid(array $data)
    {
        $group = new Group();
        $group->setName($data['name']);
        $validation = $this->validator->validate($group);
        if (0 !== count($validation)) {
            return $validation[0]->getMessage();
        }

        return true;
    }
}

