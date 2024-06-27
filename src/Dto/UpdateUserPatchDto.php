<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateUserPatchDto',
    type: 'object',
    title: 'Update User PATCH DTO',
    properties: [
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'age', type: 'integer'),
        new OA\Property(property: 'sex', type: 'string', enum: ['male', 'female']),
        new OA\Property(property: 'birthday', type: 'string', format: 'date', pattern: '^\d{4}-\d{2}-\d{2}', example: '1990-01-01'),
        new OA\Property(property: 'phone', type: 'string')
    ]
)]
class UpdateUserPatchDto extends AbstractUserWithOptionalFieldsDto
{
    public function __construct(
        ?string $email = null,
        ?string $name = null,
        ?int $age = null,
        ?string $sex = null,
        ?string $birthday = null,
        ?string $phone = null
    ) {
        parent::__construct($email, $name, $age, $sex, $birthday, $phone);
    }
}