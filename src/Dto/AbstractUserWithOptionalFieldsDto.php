<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractUserWithOptionalFieldsDto
{   
    use ValidateBirthdayAndPhoneTrait;

    public function __construct(
        #[Assert\Email(['message' => 'Email is not valid.'])]
        #[Assert\Length(['max' => 255, 'maxMessage' => 'Email cannot be longer than 255 characters.'])]
        public ?string $email = null,

        #[Assert\Length(['max' => 255, 'maxMessage' => 'Name cannot be longer than 255 characters.'])]
        public ?string $name = null,

        #[Assert\Range(['min' => 0, 'max' => 130, 'notInRangeMessage' => 'Age must be between {{ min }} and {{ max }}.'])]
        public ?int $age = null,

        #[Assert\Choice(['choices' => ['male', 'female'], 'message' => 'Sex must be either "male" or "female".'])]
        public ?string $sex = null,

        #[Assert\Callback([self::class, 'validateBirthday'])]
        public ?string $birthday = null,

        #[Assert\Callback([self::class, 'validatePhone'])]
        public ?string $phone = null
    ) {}
}
