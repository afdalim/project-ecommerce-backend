<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query()->with('product');

        if ($request->low_stock) {
            $query->whereColumn('available', '<=', 'reorder_level');
        }

        $stock = $query->paginate($request->per_page ?? 50);

        return response()->json($stock);
    }

    public function update(Request $request, $stockId)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:0',
            'reorder_level' => 'sometimes|integer|min:0',
        ]);

        $stock = Stock::findOrFail($stockId);
        $stock->update([

    'quantity' =>
    $request->quantity,


    'available' =>
    $request->quantity - $stock->reserved_quantity

]);
        $stock->updateAvailable();

        return response()->json(['message' => 'Stock updated successfully', 'stock' => $stock]);
    }

    public function reorderStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = Stock::where('product_id', $request->product_id)->firstOrFail();
        $stock->increment('quantity', $request->quantity);
        $stock->updateAvailable();

        return response()->json(['message' => 'Stock reordered successfully', 'stock' => $stock]);
    }

    public function getLowStockProducts()
    {
        $products = Stock::whereColumn('available', '<=', 'reorder_level')
            ->with('product')
            ->paginate(20);

        return response()->json($products);
    }
}
