<?php

namespace App\Interface;

use App\Entity\User;
use App\Dto\CreateUserDto;
use App\Dto\UpdateUserPutDto;
use App\Dto\UpdateUserPatchDto;

interface UserServiceInterface
{
    public function createUser(CreateUserDto $dto): User;
    public function updateUser(User $user, UpdateUserPutDto $dto): User;
    public function partialUpdateUser(User $user, UpdateUserPatchDto $dto): User;
    public function deleteUser(User $user): void;
    public function getUsers(int $page, int $limit): array;
    public function formatUserData(User $user): array;
}
