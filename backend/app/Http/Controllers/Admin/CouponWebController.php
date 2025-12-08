<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponWebController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Coupon::query();
            
            if ($request->filled('search')) {
                $query->where('code', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
            }
            
            if ($request->filled('status')) {
                $query->where('is_active', $request->status == 'active');
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            $coupons = $query->latest()->paginate(15);
            
            return view('admin.coupons.index', compact('coupons'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar cupones: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Cupón creado exitosamente');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Cupón actualizado exitosamente');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Cupón eliminado exitosamente');
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Cupón no encontrado'
            ], 404);
        }

        if (!$coupon->isValid($request->total)) {
            $message = 'Cupón inválido';
            
            if (!$coupon->is_active) {
                $message = 'Cupón inactivo';
            } elseif ($coupon->expires_at && now()->gt($coupon->expires_at)) {
                $message = 'Cupón expirado';
            } elseif ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                $message = 'Cupón agotado';
            } elseif ($coupon->min_purchase && $request->total < $coupon->min_purchase) {
                $message = "Compra mínima requerida: $" . number_format($coupon->min_purchase, 2);
            }
            
            return response()->json([
                'valid' => false,
                'message' => $message
            ], 400);
        }

        $discount = $coupon->calculateDiscount($request->total);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'message' => 'Cupón aplicado exitosamente'
        ]);
    }
}
