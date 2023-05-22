<?php

namespace App\Serializer;

use App\Entity\Expense;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ExpenseNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
    ) {
    }


    public function normalize($object, string $format = null, array $context = [])
    {

        /** @var Expense $object */
        $normalizedData = [
            'id' => $object->getId(),
            'amount' => $object->getAmount(),
            'date' => $object->getDate(),
            'type' => $object->getType(),
            'user' => [
                'id' => $object->getUser()->getId(),
                'firstname' => $object->getUser()->getFirstname(),
                'lastname' => $object->getUser()->getLastname(),
                'email' => $object->getUser()->getEmail(),
                'birthday' => $object->getUser()->getBirthday(),
                'roles' => $object->getUser()->getRoles(),
                'createdAt' => $object->getUser()->getCreatedAt(),
                'updatedAt' => $object->getUser()->getUpdatedAt(),

            ],
            'company' => [
                'id' => $object->getCompany()->getId(),
                'name' => $object->getCompany()->getName(),
                'createdAt' => $object->getCompany()->getCreatedAt(),
                'updatedAt' => $object->getCompany()->getUpdatedAt(),
            ],
            'createdAt' => $object->getCreatedAt(),
            'updatedAt' => $object->getUpdatedAt(),
        ];

        return $normalizedData;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Expense;
    }
}