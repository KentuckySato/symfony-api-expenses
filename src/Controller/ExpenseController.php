<?php

namespace App\Controller;

use App\Entity\Expense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Expense\ServiceExpenseHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/expenses/{id}', name: 'expenses.show')]
    public function show(Expense $expense): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Expense #' . $expense->getId() . ' founded.',
            'path' => '/expenses/{id}',
            'data' => $expense->toArray()
        ]);
    }

    #[Route('/expenses', name: 'expenses.create', methods: ['POST'], format: 'json')]
    public function create(Request $request): JsonResponse
    {
        // Extract data from the request
        $data = json_decode($request->getContent(), true);

        $serviceExpenseHydrate = new ServiceExpenseHydrator($this->entityManager);
        $expense = $serviceExpenseHydrate->hydrate($data);

        $this->entityManager->getRepository(Expense::class)->save($expense, true);

        return new JsonResponse(
            [
                'message' => 'Expense #' . $expense->getId() . ' created.',
                'path' => '/expenses',
                'data' => $expense->toArray()
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
