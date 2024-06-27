<?php

namespace App\Services;

use App\Entity\User;
use App\Dto\CreateUserDto;
use App\Dto\UpdateUserPatchDto;
use App\Dto\UpdateUserPutDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function createUser(CreateUserDto $dto): User
    {    
        $this->checkEmailTaken($dto->email);

        $user = new User();
        $this->mapDataToUser($user, $dto);
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(User $user, UpdateUserPutDto $dto): User
    {
        $this->checkEmailTakenByAnotherUser($dto->email, $user);

        $this->mapDataToUser($user, $dto);
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return $user;
    }

    public function partialUpdateUser(User $user, UpdateUserPatchDto $dto): User
    {   
        $fieldsToUpdate = [
            'email' => 'setEmail',
            'name' => 'setName',
            'age' => 'setAge',
            'sex' => 'setSex',
            'birthday' => 'setBirthday',
            'phone' => 'setPhone'
        ];
    
        foreach ($fieldsToUpdate as $field => $setter) {
            if (property_exists($dto, $field) && $dto->$field !== null) {
                if ($field === 'email') {
                    $this->checkEmailTakenByAnotherUser($dto->$field, $user);
                }
                if ($field === 'birthday') {
                    $date = \DateTime::createFromFormat('Y-m-d', $dto->$field);
                    $user->$setter($date);
                } else {
                    $user->$setter($dto->$field);
                }
            }
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function getUsers(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $repository = $this->entityManager->getRepository(User::class);

        $totalUsers = $repository->count([]);
        $users = $repository->findBy([], null, $limit, $offset);

        $userData = array_map([$this, 'formatUserData'], $users);

        return [
            'data' => $userData,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalUsers,
                'totalPages' => ceil($totalUsers / $limit)
            ]
        ];
    }

    public function formatUserData(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'age' => $user->getAge(),
            'sex' => $user->getSex(),
            'birthday' => $user->getBirthday()->format('Y-m-d'),
            'phone' => $user->getPhone(),
            'createdAt' => $user->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
            'updatedAt' => $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d\TH:i:s\Z') : null
        ];
    }

    private function mapDataToUser(User $user, $dto): User
    {
        $user->setEmail($dto->email);
        $user->setName($dto->name);
        $user->setAge($dto->age);
        $user->setSex($dto->sex);
        $user->setBirthday(new \DateTime($dto->birthday));
        $user->setPhone($dto->phone);
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }
    
    private function checkEmailTaken(string $email): void
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            throw new ConflictHttpException('This email is already in use.');
        }
    }

    private function checkEmailTakenByAnotherUser(string $email, User $currentUser): void
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser && $existingUser->getId() !== $currentUser->getId()) {
            throw new ConflictHttpException('This email is already in use.');
        }
    }
}