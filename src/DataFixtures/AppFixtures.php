<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->email())
            ->setRoles(['ROLE_ADMIN'])
            ->setBirthday($faker->dateTimeBetween('-50 years', '-18 years'))
            ->setCreatedAt(new \DateTime())
        ;

        $password = $this->hasher->hashPassword($user, 'symfony');
        $user->setPassword($password);

        $manager->persist($user);

        $cmpArray = [];
        // Create Companies
        for ($cmp = 0; $cmp < 3; $cmp++) {
            $company = new Company();
            $company
                ->setName($faker->company())
                ->setCreatedAt($faker->dateTimeBetween('-6 month', 'now'));

            $cmpArray[] = $company;

            $manager->persist($company);
        }

        // Create Expenses
        for ($e = 0; $e < 5; $e++) {
            $expense = new Expense();

            $expense
                ->setUser($user)
                ->setAmount($faker->randomFloat(2, 10, 100))
                ->setCompany($faker->randomElement($cmpArray))
                ->setDate($faker->dateTimeBetween('-6 month', 'now'))
                ->setType($faker->numberBetween(1, 3))
                ->setCreatedAt($faker->dateTimeBetween('-6 month', 'now'));

            $manager->persist($expense);
        }

        $manager->flush();
    }
}
