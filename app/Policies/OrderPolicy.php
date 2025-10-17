<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * User can view own specified order.
     *
     * @param User $user Authenticated user.
     * @param Order $order The order being accessed.
     * @return bool True if the user is authorized to view the order.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->role === 'buyer') {
            return $order->buyer_id === $user->id;
        }

        if ($user->role === 'seller') {
            return $order->items()->where('seller_id', $user->id)->exists();
        }

        return false;
    }

}
