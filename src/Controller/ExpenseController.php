<?php

namespace App\Controller;

use App\Entity\Expense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Expense\ServiceExpenseHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExpenseController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    #[Route('/expenses', name: 'expenses.index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $arrExpenses = $this->entityManager->getRepository(Expense::class)->findAll();

        $toArray = array_map(function ($expense) {
            /** @var Expense $expense */
            return $expense->toArray();
        }, $arrExpenses);


        return new JsonResponse(
            data: [
                'message' => count($toArray) . ' Expenses founded.',
                'path' => '/expenses',
                'data' => $toArray
            ],
        );
    }

    #[Route('/expenses/{id}', name: 'expenses.show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Expense $expense): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Expense #' . $expense->getId() . ' founded.',
            'path' => '/expenses/{id}',
            'data' => $expense->toArray()
        ]);
    }

    #[Route('/expenses', name: 'expenses.create', methods: ['POST'], format: 'json')]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Extract data from the request
        $data = json_decode($request->getContent(), true);


        $serviceExpenseHydrate = new ServiceExpenseHydrator($this->entityManager);
        $expense = $serviceExpenseHydrate->hydrate($data);

        $errors = $validator->validate($expense);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse(
                [
                    'message' => $errorsString
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $this->entityManager->getRepository(Expense::class)->save($expense, true);

        return new JsonResponse(
            [
                'message' => 'Expense #' . $expense->getId() . ' created.',
                'path' => '/expenses/' . $expense->getId(),
                'data' => $expense->toArray()
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route('/expenses/{id}', name: 'expenses.update', methods: ['PUT'], requirements: ['id' => '\d+'], format: 'json')]
    public function update(Request $request, Expense $expense, ValidatorInterface $validator): JsonResponse
    {
        // Extract data from the request
        $data = json_decode($request->getContent(), true);

        $serviceExpenseHydrate = new ServiceExpenseHydrator($this->entityManager);
        $expense = $serviceExpenseHydrate->hydrate($data, $expense);
        $errors = $validator->validate($expense);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse(
                [
                    'message' => $errorsString
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $this->entityManager->getRepository(Expense::class)->flush();

        return new JsonResponse(
            [
                'message' => 'Expense #' . $expense->getId() . ' updated.',
                'path' => '/expenses/' . $expense->getId(),
                'data' => $expense->toArray()
            ],
            JsonResponse::HTTP_OK
        );
    }
}
