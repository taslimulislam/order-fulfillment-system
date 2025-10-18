<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    /**
     * Initialize OrderService dependency.
     *
     * @param OrderService $orderService order process handeling.
     */
    public function __construct(private readonly OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
        if (! $request->user()) {
            return ApiResponse::error('Auth user not found', 401);
        }
        return $next($request);
    });

    }

    /**
     * Creates a new order for buyer.
     *
     * @param StoreOrderRequest $request Validated order data.
     * @return \Illuminate\Http\JsonResponse JSON response with order details.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->user(), $request->validated());

            return ApiResponse::success([
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'status'       => $order->status,
                'total_amount' => $order->total_amount,
            ], 'Order placed successfully', 201);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
        return ApiResponse::error('Failed to place order. Please try again later.', 500);
    }

    /**
     * View specific order details.
     *
     * @param Order $order order model instance.
     * @return \Illuminate\Http\JsonResponse JSON response with order data.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product', 'buyer');

        return ApiResponse::success($order);
    }

}
