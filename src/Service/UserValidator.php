<?php

namespace App\Service;

use App\Entity\User;

/**
 * Class UserValidator
 * @package App\Service
 */
class UserValidator extends Validator
{
    /**
     * Validate request with defined entity rules
     *
     * @param array $data
     * @return mixed
     */
    public function isValid(array $data)
    {
        $user = new User();
        $user->setName($data['name']);
        $validation = $this->validator->validate($user);
        if (0 !== count($validation)) {
            return $validation[0]->getMessage();
        }

        return true;
    }
}

