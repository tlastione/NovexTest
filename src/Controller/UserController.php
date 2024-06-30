<?php

namespace App\Controller;

use App\Entity\User;
use App\Dto\CreateUserDto;
use App\Dto\UpdateUserPutDto;
use App\Dto\UpdateUserPatchDto;
use App\Interface\UserServiceInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

#[Route('/api/users', format: 'json')]
class UserController extends AbstractController
{
    public function __construct(
        private UserServiceInterface $userService,
    ){}
    
    #[OA\Post(
        path: '/users',
        summary: 'Create a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateUserDto')
        ),
    )]
    #[Route('', name: 'create_user', methods: ['POST'])]
    public function createUser(
        #[MapRequestPayload] CreateUserDto $dto
    ): JsonResponse
    {   
        $user = $this->userService->createUser($dto);
        return $this->json([
            'message' => 'User created successfully', 
            'user' => $user
        ], JsonResponse::HTTP_CREATED);
    }
    
    #[OA\Put(
        path: '/users/{id}',
        summary: 'Update an existing user',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user to update',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserPutDto')
        )
    )]
    #[Route('/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        User $user,
        #[MapRequestPayload] UpdateUserPutDto $dto
    ): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $dto);
        return $this->json([
            'message' => 'User updated successfully', 
            'user' => $updatedUser
        ], JsonResponse::HTTP_CREATED);
    }

    #[OA\Patch(
        path: '/users/{id}',
        summary: 'Partially update an existing user',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user to partially update',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserPatchDto')
        )
    )]
    #[Route('/{id}', name: 'partial_update_user', methods: ['PATCH'])]
    public function partialUpdateUser(
        User $user,
        #[MapRequestPayload] UpdateUserPatchDto $dto
    ): JsonResponse
    {   
        $updatedUser = $this->userService->partialUpdateUser($user, $dto);
        return $this->json([
            'message' => 'User updated successfully', 
            'user' => $updatedUser
        ], JsonResponse::HTTP_CREATED);
    }

    #[OA\Delete(
        path: '/users/{id}',
        summary: 'Delete an existing user',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user to delete',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);
        return $this->json([
            'message' => 'User deleted successfully.'
        ], JsonResponse::HTTP_OK);
    }

    #[OA\Get(
        path: '/users',
        summary: 'Get all users',
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number',
                required: false,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                description: 'Number of users per page',
                required: false,
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[Route('', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10
    ): JsonResponse {
        $result = $this->userService->getUsers($page, $limit);

        return $this->json([
            'data' => $result['data'],
            'meta' => $result['meta']
        ], JsonResponse::HTTP_OK);
    }

    #[OA\Get(
        path: '/users/{id}',
        summary: 'Get a user by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ]
    )]
    #[Route('/{id}', name: 'get_user_by_id', methods: ['GET'])]
    public function getUserById(User $user): JsonResponse
    {
        return $this->json([
            'data' => $user
        ], JsonResponse::HTTP_OK);
    }
}