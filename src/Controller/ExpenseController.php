<?php

namespace App\Controller;

use App\Entity\Expense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ExpenseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController extends AbstractController
{

    public function __construct(public ExpenseRepository $repository)
    {
    }

    #[Route('/expenses', name: 'expenses.index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        if ($request->isMethod('GET')) {

            // Get all expenses
            // Use ExpenseRepository to get all expenses
            // Return a JsonResponse with all expenses
            $arrExpenses = $this->repository->findAll();

            return new JsonResponse([
                'message' => 'GET method',
                'path' => '/expenses',
                'data' => $arrExpenses
            ]);
        }
    }

    #[Route('/expenses/{id}', name: 'expenses.show')]
    public function show(Expense $expense): JsonResponse
    {

        return new JsonResponse([
            'message' => 'GET method',
            'path' => '/expenses/{id}',
            'data' => $expense->toArray()
        ]);
    }

    // @todo Create a new expense
    // #[Route('/expenses', name: 'expenses.create', methods: ['POST'])]
    // public function create(Request $request): JsonResponse
    // {
    //     $expense = new Expense();
    //     dd($request);
    //     $expense->setType($request->get('type'));
    //     $expense->setAmount($request->get('amount'));
    //     $expense->setDate($request->get('date'));
    //     $expense->setUserId($request->get('user_id'));
    //     $expense->setCompanyId($request->get('company_id'));


    //     // Create an expense
    //     // Use ExpenseRepository to create an expense
    //     // Return a JsonResponse with the created expense
    //     $expense = $this->repository->save($expense);

    //     return new JsonResponse([
    //         'message' => 'POST method',
    //         'path' => '/expenses',
    //         'data' => $expense
    //     ]);
    // }
}
