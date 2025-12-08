<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'tenant_admin' || $user->role === 'super_admin';
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'tenant_admin' && $user->tenant_id === $product->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'tenant_admin' && $user->tenant_id !== null;
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'tenant_admin' && $user->tenant_id === $product->tenant_id;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'tenant_admin' && $user->tenant_id === $product->tenant_id;
    }
}
