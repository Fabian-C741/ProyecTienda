<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'tenant_admin' || $user->role === 'super_admin';
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'tenant_admin') {
            return $user->tenant_id === $order->tenant_id;
        }

        // Los clientes pueden ver sus propias órdenes
        return $user->id === $order->user_id;
    }

    public function update(User $user, Order $order): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'tenant_admin' && $user->tenant_id === $order->tenant_id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'super_admin';
    }
}
