<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Display Material List
    public function index()
    {
        $materials = Material::all();
        return view('materials.index', compact('materials'));
    }

    // Store New Material OR Add Stock if Item Code Exists
    public function store(Request $request)
    {
        $request->validate([
            'item_code'         => 'required|string',
            'name'              => 'required|string',
            'category'          => 'required|string',
            'quantity_in_stock' => 'required|integer|min:1',
            'unit'              => 'required|string',
            'min_stock_level'   => 'required|integer|min:1',
        ]);

        // Check if item code already exists
        $existingMaterial = Material::where('item_code', $request->item_code)->first();

        if ($existingMaterial) {
            // Update existing stock balance
            $oldStock   = $existingMaterial->quantity_in_stock;
            $addedStock = (int) $request->quantity_in_stock;
            $newStock   = $oldStock + $addedStock;

            $existingMaterial->update([
                'quantity_in_stock' => $newStock,
                'name'              => $request->name,
                'category'          => $request->category,
                'unit'              => $request->unit,
                'min_stock_level'   => $request->min_stock_level,
            ]);

            // Create Audit Trail for existing item
            AuditTrail::create([
                'user_id'      => auth()->id(),
                'material_id'  => $existingMaterial->id,
                'action'       => 'Stock Added (Existing Item)',
                'stock_change' => '+' . $addedStock,
                'remarks'      => 'Added ' . $addedStock . ' ' . $request->unit . ' to existing item ' . $existingMaterial->item_code,
            ]);

            return back()->with('success', 'Stock balance updated and recorded in Audit Trail!');
        }

        // Create new material record
        $material = Material::create([
            'item_code'         => $request->item_code,
            'name'              => $request->name,
            'category'          => $request->category,
            'quantity_in_stock' => $request->quantity_in_stock,
            'unit'              => $request->unit,
            'min_stock_level'   => $request->min_stock_level,
        ]);

        // Create Audit Trail for new item
        AuditTrail::create([
            'user_id'      => auth()->id(),
            'material_id'  => $material->id,
            'action'       => 'New Material Registered',
            'stock_change' => '+' . $request->quantity_in_stock,
            'remarks'      => 'Newly registered item added to inventory stock',
        ]);

        return back()->with('success', 'New material added and recorded in Audit Trail!');
    }

    // Direct Inline Stock Adjustment
    public function updateStock(Request $request, Material $material)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $oldStock = $material->quantity_in_stock;
        $newStock = (int) $request->quantity;
        $diff     = $newStock - $oldStock;

        if ($diff === 0) {
            return back()->with('error', 'No stock quantity changes were made.');
        }

        $stockChangeFormatted = ($diff > 0) ? '+' . $diff : (string)$diff;

        $material->update([
            'quantity_in_stock' => $newStock,
        ]);

        AuditTrail::create([
            'user_id'      => auth()->id(),
            'material_id'  => $material->id,
            'action'       => 'Stock Manual Adjustment',
            'stock_change' => $stockChangeFormatted,
            'remarks'      => 'Stock level updated from ' . $oldStock . ' to ' . $newStock . ' ' . $material->unit,
        ]);

        return back()->with('success', 'Stock updated and logged in Audit Trail!');
    }
}