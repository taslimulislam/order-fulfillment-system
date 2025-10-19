<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Services;

use App\Models\User;
use App\Repositories\OrderReportRepository;

class OrderReportService
{
    protected OrderReportRepository $repository;

    /**
     * Inject the order report repository.
     */
    public function __construct(OrderReportRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generate a report based on the user's role.
     *
     * @param User $user
     * @return array
     */
    public function roleBaseOrders(User $user): array
    {
        if ($user->role === 'seller') {
            return [
                'role' => 'seller',
                'seller_products' => $this->repository->getSellerProducts($user->id),
                'related_orders' => $this->repository->getOrdersWithSellerProducts($user->id),
            ];
        }

        if ($user->role === 'buyer') {
            return [
                'role' => 'buyer',
                'orders' => $this->repository->getBuyerOrders($user->id),
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Unsupported role or unauthorized access.',
        ];
    }
}