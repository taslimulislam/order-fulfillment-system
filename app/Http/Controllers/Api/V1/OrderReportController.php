<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\OrderReportService;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderReportController extends Controller
{
    protected OrderReportService $service;

    /**
     * Inject the order report service.
     */
    public function __construct(OrderReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Generate a role-based order report for the authenticated user.
     *
     * @return JsonResponse
     */
    public function roleBasedOrderReport()
    {
        try {
            $user = Auth::user();

            $report = $this->service->roleBaseOrders($user);

            if (isset($report['status']) && $report['status'] === 'error') {
                return ApiResponse::error($report['message'], 403);
            }

            return ApiResponse::success($report);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }

    }

}
