<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package App\Service
 */
class Validator
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Validator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Parse json request into array
     *
     * @param Request $request
     * @return array|null
     */
    public function jsonBodyOf(Request $request): ?array
    {
        return json_decode($request->getContent(), true);
    }
}

