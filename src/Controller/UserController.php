<?php

namespace App\Controller;

use App\Service\UserValidator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="createUser", methods="POST")
     *
     * @param Request $request
     * @param UserRepository $user
     * @param UserValidator $validator
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, UserRepository $user, UserValidator $validator)
    {
        // Parse json request
        $params = $validator->jsonBodyOf($request);
        if ($params === null) {
            return new JsonResponse(
                ['message' => 'Invalid user request!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Check request validation
        $validation = $validator->isValid($params);
        if ($validation !== true) {
            return new JsonResponse(
                ['message' => $validation],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Create new user
        if ($user->add($params)) {
            return new JsonResponse(
                ['message' => 'User has been added successfully!'],
                Response::HTTP_OK
            );
        }
    }

    /**
     * @Route("/user/{id}", name="deleteUser", methods="DELETE", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param UserRepository $user
     * @return JsonResponse
     */
    public function delete(int $id, UserRepository $user)
    {
        if ($user->remove($id)) {
            return new JsonResponse(
                ['message' => 'User has been removed successfully!'],
                Response::HTTP_OK
            );
        }

        return new JsonResponse(
            ['message' => 'User not exist!'],
            Response::HTTP_NOT_FOUND
        );
    }
}

