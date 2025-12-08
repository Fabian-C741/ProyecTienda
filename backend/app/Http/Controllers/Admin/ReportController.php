<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now());

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items', 'user'])
            ->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $salesByStatus = $orders->groupBy('status')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total')
            ];
        });

        return view('admin.reports.sales', compact(
            'orders',
            'totalSales',
            'totalOrders',
            'averageOrder',
            'salesByStatus',
            'startDate',
            'endDate'
        ));
    }

    public function exportOrders(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now());

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'user'])
            ->get();

        $filename = 'ordenes_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID Orden',
                'Número Orden',
                'Cliente',
                'Email',
                'Teléfono',
                'Total',
                'Estado',
                'Fecha',
                'Productos'
            ]);

            // Data
            foreach ($orders as $order) {
                $products = $order->items->map(function($item) {
                    return $item->product_name . ' (x' . $item->quantity . ')';
                })->implode(', ');

                fputcsv($file, [
                    $order->id,
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->total,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i'),
                    $products
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
