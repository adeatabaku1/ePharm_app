<?php

namespace App\Services\Pharmacy;

use App\Models\Medicine;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\DB;

class MedicineService
{
    /**
     * Get medicines for a pharmacy with optional filters
     * 
     * @param int $pharmacyId
     * @param string|null $search
     * @param string|null $category
     * @param string|null $stockLevel
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getMedicines($pharmacyId, $search = null, $category = null, $stockLevel = null)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        $query = Medicine::where('tenant_id', $pharmacy->tenant_id);
            
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }
        
        // Apply stock level filter
        if ($stockLevel) {
            switch ($stockLevel) {
                case 'low':
                    $query->where('stock_quantity', '<', 10);
                    break;
                case 'out':
                    $query->where('stock_quantity', 0);
                    break;
                case 'expiring':
                    $query->where('expire_date', '<=', now()->addMonths(3));
                    break;
            }
        }
        
        return $query->orderBy('name')->paginate(20);
    }
    
    /**
     * Get a specific medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @return Medicine
     */
    public function getMedicine($pharmacyId, $medicineId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        return Medicine::where('tenant_id', $pharmacy->tenant_id)
            ->where('id', $medicineId)
            ->firstOrFail();
    }
    
    /**
     * Create a new medicine
     * 
     * @param int $pharmacyId
     * @param array $data
     * @return Medicine
     */
    public function createMedicine($pharmacyId, array $data)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        $medicine = new Medicine();
        $medicine->tenant_id = $pharmacy->tenant_id;
        $medicine->name = $data['name'];
        $medicine->description = $data['description'] ?? null;
        $medicine->price = $data['price'];
        $medicine->stock_quantity = $data['stock_quantity'];
        $medicine->expire_date = $data['expire_date'];
        $medicine->category = $data['category'] ?? null;
        $medicine->manufacturer = $data['manufacturer'] ?? null;
        $medicine->created_by = auth()->id();
        $medicine->save();
        
        return $medicine;
    }
    
    /**
     * Update a medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @param array $data
     * @return Medicine
     */
    public function updateMedicine($pharmacyId, $medicineId, array $data)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        $medicine = Medicine::where('tenant_id', $pharmacy->tenant_id)
            ->where('id', $medicineId)
            ->firstOrFail();
            
        $medicine->name = $data['name'];
        $medicine->description = $data['description'] ?? $medicine->description;
        $medicine->price = $data['price'];
        $medicine->stock_quantity = $data['stock_quantity'];
        $medicine->expire_date = $data['expire_date'];
        $medicine->category = $data['category'] ?? $medicine->category;
        $medicine->manufacturer = $data['manufacturer'] ?? $medicine->manufacturer;
        $medicine->updated_by = auth()->id();
        $medicine->save();
        
        return $medicine;
    }
    
    /**
     * Delete a medicine
     * 
     * @param int $pharmacyId
     * @param int $medicineId
     * @return bool
     */
    public function deleteMedicine($pharmacyId, $medicineId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        return Medicine::where('tenant_id', $pharmacy->tenant_id)
            ->where('id', $medicineId)
            ->delete();
    }
    
    /**
     * Get medicine categories
     * 
     * @param int $pharmacyId
     * @return array
     */
    public function getMedicineCategories($pharmacyId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        return Medicine::where('tenant_id', $pharmacy->tenant_id)
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->toArray();
    }
}
