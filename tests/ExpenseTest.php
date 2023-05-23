<?php

namespace App\Tests;

use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\User;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class ExpenseTest extends TestCase
{
    public function testExpenseIsTrue(): void
    {
        $expense = new Expense();
        $expense
            ->setType(2)
            ->setAmount(10)
            ->setDate(new \DateTime())
            ->setCompany(new Company())
            ->setUser(new User())
        ;

        $this->assertEquals(2, $expense->getType());
        $this->assertEquals(10, $expense->getAmount());
        $this->assertInstanceOf(DateTimeInterface::class, $expense->getDate());
        $this->assertInstanceOf(Company::class, $expense->getCompany());
        $this->assertInstanceOf(User::class, $expense->getUser());
    }
}
