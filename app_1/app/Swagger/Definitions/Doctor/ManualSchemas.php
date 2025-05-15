<?php

/**
 * @OA\Schema(
 *     schema="MedicineResource",
 *     type="object",
 *     required={"id", "tenant_id", "name", "price", "stock_quantity"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=101),
 *     @OA\Property(property="name", type="string", example="Paracetamol"),
 *     @OA\Property(property="description", type="string", example="Pain relief medicine"),
 *     @OA\Property(property="price", type="number", format="float", example=5.99),
 *     @OA\Property(property="stock_quantity", type="integer", example=120),
 *     @OA\Property(property="expire_date", type="string", format="date", example="2025-12-31"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
 * )
 */
