<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateUserPutDto',
    type: 'object',
    title: 'Update User PUT DTO',
    required: ['email', 'name', 'age', 'sex', 'phone', 'birthday'],
    properties: [
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'age', type: 'integer'),
        new OA\Property(property: 'sex', type: 'string', enum: ['male', 'female']),
        new OA\Property(property: 'birthday', type: 'string', format: 'date', pattern: '^\d{4}-\d{2}-\d{2}', example: '1990-01-01'),
        new OA\Property(property: 'phone', type: 'string')
    ]
)]
class UpdateUserPutDto extends AbstractUserWithAllFieldsDto
{
    public function __construct(
        string $email,
        string $name,
        int $age,
        string $sex,
        string $birthday,
        string $phone
    ) {
        parent::__construct($email, $name, $age, $sex, $birthday,  $phone);
    }
}