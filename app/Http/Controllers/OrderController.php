<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Product;
use App\Services\OrderReportService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Exception;


class OrderController extends Controller
{

    public function __construct(
        protected OrderReportService $service
    ) {}

    /**
     * Show orders for authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $user = auth()->user();
        $report  = $this->service->roleBaseOrders($user);
        $balance = $user->role === 'seller' ? $user->balance : null;

        return view('orders.index', compact('report', 'balance', 'user'));
    }

    /**
     * Show order creation form for buyers.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $products = Product::all();

        return view('orders.create', compact('products'));
    }

    /**
 * Store a new order from the web interface.
 *
 * @param StoreOrderRequest $request
 * @return RedirectResponse
 */
public function store(StoreOrderRequest $request, OrderService $orderService): RedirectResponse
{
    try {
        $order = $orderService->createOrder(Auth::user(), $request->validated());

        return redirect()
        ->route('orders.index')
        ->with('success', 'Order placed successfully. Order #' . $order->order_number);
        
    } catch (Exception $e) {
        return redirect()
        ->route('orders.create')
        ->withInput()
        ->withErrors(['order' => 'Failed to place order: ' . $e->getMessage()]);
    }
}

}
