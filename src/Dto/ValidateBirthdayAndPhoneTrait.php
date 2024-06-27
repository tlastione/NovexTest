<?php

namespace App\Dto;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

trait ValidateBirthdayAndPhoneTrait
{
    #[Assert\Callback]
    public static function validateBirthday($object, ExecutionContextInterface $context): void
    {
        if (!empty($object->birthday)) {
            $date = \DateTime::createFromFormat('Y-m-d', $object->birthday);
            if (!$date || $date->format('Y-m-d') !== $object->birthday) {
                $context->buildViolation('Birthday must be a valid date in the format YYYY-MM-DD.')
                    ->atPath('birthday')
                    ->addViolation();
            }
        }
    }

    #[Assert\Callback]
    public static function validatePhone($object, ExecutionContextInterface $context): void
    {   
        if (!empty($object->phone)) {
            if (strlen($object->phone) < 10 || strlen($object->phone) > 15) {
                $context->buildViolation('Phone must be between 10 and 15 characters long.')
                    ->atPath('phone')
                    ->addViolation();
            }

            if (!preg_match('/^\+?\d+(\s\d+)*$/', $object->phone)) {
                $context->buildViolation('Invalid phone number.')
                    ->atPath('phone')
                    ->addViolation();
            }
        }
    }
}