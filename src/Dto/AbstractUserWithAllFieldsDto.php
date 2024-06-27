<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractUserWithAllFieldsDto
{   
    use ValidateBirthdayAndPhoneTrait;

    public function __construct(
        #[Assert\NotBlank(['message' => 'Email is required.'])]
        #[Assert\Email(['message' => 'Email is not valid.'])]
        #[Assert\Length(['max' => 255, 'maxMessage' => 'Email cannot be longer than 255 characters.'])]
        public string $email,

        #[Assert\NotBlank(['message' => 'Name is required.'])]
        #[Assert\Length(['max' => 255, 'maxMessage' => 'Name cannot be longer than 255 characters.'])]
        public string $name,

        #[Assert\NotBlank(['message' => 'Age is required.'])]
        #[Assert\Range(['min' => 0, 'max' => 130, 'notInRangeMessage' => 'Age must be between {{ min }} and {{ max }}.'])]
        public int $age,

        #[Assert\NotBlank(['message' => 'Sex is required.'])]
        #[Assert\Choice(['choices' => ['male', 'female'], 'message' => 'Sex must be either "male" or "female".'])]
        public string $sex,

        #[Assert\NotBlank(['message' => 'Birthday is required.'])]
        #[Assert\Callback([self::class, 'validateBirthday'])]
        public string $birthday,

        #[Assert\NotBlank(['message' => 'Phone is required.'])]
        #[Assert\Length(['min' => 10, 'max' => 15, 'minMessage' => 'Phone must be at least {{ limit }} characters long.', 'maxMessage' => 'Phone cannot be longer than {{ limit }} characters.'])]
        #[Assert\Regex(['pattern' => '/^\+?\d+(\s\d+)*$/', 'message' => 'Invalid phone number.'])]
        public string $phone
    ) {}
}
