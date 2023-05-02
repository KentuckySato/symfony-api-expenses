<?php

namespace App\Controller;

use App\Repository\ExpenseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController
{

    public function __construct(public ExpenseRepository $repository)
    {
    }

    #[Route('/expenses')]
    public function index(Request $request)
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
}