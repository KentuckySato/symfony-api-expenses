<?php

declare(strict_types=1);

namespace App\Service\Expense;

use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ServiceExpenseHydrator
{

    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    public function hydrate(array $data): Expense
    {
        $expense = new Expense();

        if (!empty($data['type'])) {
            $expense->setType($data['type']);
        }
        if (!empty($data['amount'])) {
            $expense->setAmount($data['amount']);
        }
        if (!empty($data['date'])) {

            $expense->setDate(
                \DateTime::createFromFormat('Y-m-d', $data['date'])
            );
        }

        // Find user and company by id
        if (!empty($data['user_id'])) {
            $user = $this->entityManager->getRepository(User::class)->find($data['user_id']);
            $expense->setUser($user);
        }
        if (!empty($data['company_id'])) {
            $company = $this->entityManager->getRepository(Company::class)->find($data['company_id']);
            $expense->setCompany($company);
        }

        return $expense;
    }
}
