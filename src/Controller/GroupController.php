<?php

namespace App\Controller;

use App\Repository\GroupRepository;
use App\Service\GroupValidator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @Route("/group", name="createGroup", methods="POST")
     *
     * @param Request $request
     * @param GroupRepository $group
     * @param GroupValidator $validator
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, GroupRepository $group, GroupValidator $validator)
    {
        // Parse json request
        $params = $validator->jsonBodyOf($request);
        if ($params === null) {
            return new JsonResponse(
                ['message' => 'Invalid group request!'],
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

        // Create new group
        if ($group->add($params)) {
            return new JsonResponse(
                ['message' => 'Group has been created successfully!'],
                Response::HTTP_OK
            );
        }
    }

    /**
     * @Route("/group/{id}", name="deleteGroup", methods="DELETE", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param GroupRepository $group
     * @return JsonResponse
     */
    public function delete(int $id, GroupRepository $group)
    {
        $group = $group->remove($id);
        if ($group === false) {
            return new JsonResponse(
                ['message' => 'Group contains member!'],
                Response::HTTP_FORBIDDEN
            );
        } elseif ($group) {
            return new JsonResponse(
                ['message' => 'Group has been removed successfully!'],
                Response::HTTP_OK
            );
        }

        return new JsonResponse(
            ['message' => 'This group not exist!'],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Route("/group/user", name="assignMember", methods="POST")
     *
     * @param Request $request
     * @param GroupRepository $group
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function assignMember(Request $request, GroupRepository $group)
    {
        // Parse json request and assign member to a group
        $params = json_decode($request->getContent(), true);
        $isAssigned = $group->assignUser($params);
        if ($isAssigned) {
            return new JsonResponse(
                ['message' => 'This member has been assigned to this group!'],
                Response::HTTP_OK
            );
        } elseif ($isAssigned === null) {
            return new JsonResponse(
                ['message' => 'This member already assigned to this group!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            ['message' => 'Group or member not exist!'],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route(
     *     "/group/{gid}/user/{uid}",
     *     name="deleteMember",
     *     methods="DELETE",
     *     requirements={"gid"="\d+", "uid"="\d+"}
     * )
     *
     * @param Request $request
     * @param GroupRepository $group
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteMember(Request $request, GroupRepository $group)
    {
        // Remove user from group
        $isRemoved = $group->removeUser($request);
        if ($isRemoved) {
            return new JsonResponse(
                ['message' => 'This member has been removed from this group!'],
                Response::HTTP_OK
            );
        } elseif ($isRemoved === null) {
            return new JsonResponse(
                ['message' => 'This member not found in this group!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            ['message' => 'Group or member not exist!'],
            Response::HTTP_BAD_REQUEST
        );
    }
}

